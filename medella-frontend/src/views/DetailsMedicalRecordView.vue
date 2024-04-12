<template>
  <div class="">
    <div class="container">
      <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-2 ms-md-2 ms-sm-0">
          <button @click="back" class="btn btnBack">
            <i class="fi fi-rs-arrow-left backarrow backarrow"></i>
          </button>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-8 offset-lg-3 offset-md-2 offset-sm-0 mt-md-2 col-9">
          <span id="title" class="fw-medium mx-auto h3">Medical Record</span>
        </div>
        <div class="col-lg-1 offset-lg-2 col-md-1 offset-md-1 col-sm-4 mt-md-2 mt-lg-1 col-3">
          <!-- this button only need to show to patient, need to add dynamic ui concept after all development is done -->
          <button
            class="btn btnDownload"
            v-show="user?.role_id === 1 || user?.role_id === 4"
            @click="convertToPdf"
          >
            <i class="fi fi-rr-download"></i>&nbsp;Download
          </button>
        </div>
      </div>
      <div class="row mt-md-5 mb-md-5">
        <div class="col-8 offset-2 offset-md-3 offset-lg-4">
          <table class="table table-borderless mt-2">
            <tbody>
              <tr>
                <td class="fw-medium">Date</td>
                <td>{{ record?.created_at }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Patient ID</td>
                <td>{{ record?.patient_id }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Patient</td>
                <td>{{ record?.patient_name }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Doctor</td>
                <td>{{ record?.doctor_name }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Status</td>
                <td>{{ record?.inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Disease</td>
                <td>{{ record?.disease }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Weight</td>
                <td>{{ record?.weight }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Blood Pressure</td>
                <td>{{ record?.blood_pressure }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Blood Glucose</td>
                <td>{{ record?.blood_glucose }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Heart Rate</td>
                <td>{{ record?.heart_rate }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Repository Rate</td>
                <td>{{ record?.repository_rate }}</td>
              </tr>
              <tr>
                <td class="fw-medium">Oxygen Level</td>
                <td>{{ record?.oxygen_level }}</td>
              </tr>

              <tr>
                <td class="fw-medium">Temperature</td>
                <td>{{ record?.temperature }}</td>
              </tr>
              <tr v-show="record.prescription !== 'undefined' && record.prescription !== ''">
                <td class="fw-medium">Prescription</td>
                <td>{{ record?.prescription }}</td>
              </tr>
              <tr v-show="record.clinical_notes !== 'undefined' && record.clinical_notes !== ''">
                <td class="fw-medium">Clinical Notes</td>
                <td>{{ record?.clinical_notes }}</td>
              </tr>
              <tr v-show="images">
                <td class="fw-medium">Files</td>
                <td>
                  <div class="d-flex flex-wrap">
                    <div class="" v-for="(img, index) in images" :key="index">
                      <img
                        :src="`http://127.0.0.1:8000/files/${img}`"
                        class="file_image d-inline me-2 rounded"
                        alt=""
                      />
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { jsPDF } from 'jspdf'
import router from '@/router'
import { onMounted, ref } from 'vue'

const record = ref({})
const images = ref([])
const user = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))

const convertToPdf = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/download/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    // const blob = await data.data.blob()
    console.log('DATA>>>>>>>>', data.pdf_url)
    window.open(data.pdf_url, '_blank')
  } catch (error) {
    console.log(error)
  }
}
onMounted(async () => {
  await getMedicalRecord()
  await getUser()
})
const back = () => {
  router.back()
}
const getUser = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/me', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      user.value = data.data

      console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const getMedicalRecord = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/medical-records/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: 'Bearer ' + localStorage.getItem('accessToken')
      }
    })
    const data = await response.json()
    if (response.ok) {
      // console.log('OKKKKKAYYYYYYY', 'http://127.0.0.1:8000/' + data.data.file_records[0])
      record.value = data.data
      images.value = data.data.file_records
      const modifiedLinks = images.value
        .map((image) => `http://127.0.0.1:8000/files/${image}`)
        .join('\n')

      imageContainer.value += modifiedLinks
      // console.log('Image Container', imageContainer.value)
    } else {
      console.log('ERRRORRRR', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
</script>

<style scoped>
.file_image {
  width: 100px;
  height: 100px;
  object-fit: cover;
}
.backarrow {
  font-size: 30px;
  color: #343a40;
}

.backarrow:hover {
  color: #4da8f5;
}
.btnBack:active {
  border: 2px solid #fff;
}
.btnDownload {
  background-color: #74b9f3;
  border: 0;
  color: #fff;
}
.btnDownload:hover {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}
.hide {
  display: none;
}
</style>
