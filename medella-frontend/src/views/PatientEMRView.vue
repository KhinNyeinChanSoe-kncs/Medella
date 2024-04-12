<template>
  <div>
    <div class="row">
      <div class="col-md-1 ms-md-2 offset-md-1">
        <button @click="back" class="btn btnBack ms-3">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div
        class="col-md-6 col-sm-9 offset-sm-1 offset-md-0 ms-sm-5 ms-md-0 h3 d-flex mt-md-2 ms-1"
        alt="Patient List Heading"
      >
        Patient {{ patient.user_name }}'s Medical Records
      </div>
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm">
          <div class="card-body overflow-x-auto">
            <table class="table" v-if="emrList.length > 0">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Type</th>
                  <th scope="col">Disease</th>
                  <th scope="col">Doctor</th>

                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(emr, index) in emrList" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ emr?.inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}</td>
                  <td>{{ emr?.disease }}</td>
                  <td>{{ emr.doctor_name }}</td>

                  <td>
                    <button class="btn btnDetail btn-sm me-2" @click="getDetails(emr.id)">
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            <div v-if="emrList.length === 0" class="text-center">
              <img
                src="./../assets/404.svg"
                alt=""
                class="notFoundImg d-inline justify-content-center mb-2"
              />
              <h3 class="fw-medium">
                Currently, There is no medical records for Patient {{ patient.user_name }}
              </h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-1"></div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const emrList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const patient = ref({})
onMounted(async () => {
  await getPatientInfo()
  await getEMR()
})
const getDetails = (id) => {
  router.push(`/medical-records/${id}`)
}
const getPatientInfo = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/patients/${router.currentRoute.value.params.id}`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
      }
    )
    const data = await response.json()
    if (response.ok) {
      patient.value = data.data
      console.log('current patient information : ', data.data)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e.message)
  }
}
const getEMR = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/patients/${router.currentRoute.value.params.id}/emrs`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
      }
    )
    const data = await response.json()
    if (response.ok) {
      emrList.value = data.data
      console.log('record list : ', data.data)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e.message)
  }
}

const back = () => {
  // router.push(`/patients/${router.currentRoute.value.params.id}`)
  router.back()
}
</script>

<style scoped>
.notFoundImg {
  width: 150px;
  height: 150px;
}
.backarrow {
  font-size: 30px;
  color: #343a40;
}
.backarrow:hover {
  color: #4da8f5;
}
* {
  color: #343a40;
}
.search-container,
.search-container form {
  /* width: 350px; */
  display: flex;
}
#searchInput {
  width: 280px;
  color: #343a40;
}

.text-button {
  display: flex;
  align-items: center;
  justify-content: center;
}

.actionBtn {
  background-color: #74b9f3;
  border: 0;
  color: #fff;
}
.actionBtn:hover {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}
.btnDetail {
  border: 2px solid #c9e3f9;
}

.btnDetail:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnDetail:active {
  border: 2px solid #74b9f3;
}
.page-item {
  color: #343a40;
}
.page-link,
.page-link span {
  text-decoration: none;
  color: #343a40;
}
.page-link:hover,
.page-link span:hover {
  color: #74b9f3;
  cursor: pointer;
}
.id-column {
  width: 50px !important;
}
.btnBack:active {
  border: 2px solid #fff;
}
</style>
