<template>
  <div>
    <div class="row">
      <div class="col-md-1 ms-md-2 offset-md-1 col-4">
        <button @click="back" class="btn btnBack ms-3">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-6 offset-md-1 offset-0 h3 mt-md-2 col-8 mt-2" alt="Patient List Heading">
        Dr.&nbsp;{{ doctorName }}'s Patient List
      </div>
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-1 col-md-10">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">PID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Type</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="patient in patientList" :key="patient.id">
                  <td class="id-column">{{ patient.id }}</td>
                  <td>{{ patient.patient_id }}</td>
                  <td>{{ patient.user_name }}</td>
                  <td>{{ patient.user_email }}</td>
                  <td>{{ patient.user_phone }}</td>
                  <td>{{ patient.inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}</td>
                  <td>
                    <button
                      class="btn btnDetail btn-sm me-2"
                      @click="getPatientDetails(patient.patient_id)"
                    >
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
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

const patientList = ref([])
const user = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
const doctorName = ref('')
onMounted(async () => {
  await getPatientofDoctor()
  doctorName.value = router.currentRoute.value.query.name
  await getUser
})
const getPatientDetails = (patient_id) => {
  router.push(`/patients/${patient_id}`)
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
      // if (user.value.role_id === 4) {
      // }
      console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}

const getPatientofDoctor = async () => {
  try {
    //retrieve doctor_id from param
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/doctors/${id}/patients`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      console.log('patient list > > >', data.data)
      patientList.value = data.data

      //   console.log('response data', data.data)
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const back = () => {
  router.back()
}
</script>

<style scoped>
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
