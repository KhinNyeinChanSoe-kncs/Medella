<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-1 col-md-4 col-sm-3 col-3 ms-md-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-6 offset-lg-2 col-md-6 col-sm-5 col-9 mt-md-2">
        <span id="title" class="fw-medium">Doctor Information</span>
        <!-- mx-auto -->
      </div>
    </div>
    <div class="row mt-md-5">
      <div class="col-8 offset-3">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td class="fw-medium">User ID</td>
              <td>{{ doctor?.id }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Doctor ID</td>
              <td>{{ doctor?.doctor_id }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Name</td>
              <td>{{ doctor?.name }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Email</td>
              <td>{{ doctor?.email }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Phone</td>
              <td>{{ doctor?.phone }}</td>
            </tr>
            <tr>
              <td class="fw-medium">City</td>
              <td>{{ doctor?.city }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Gender</td>
              <td>{{ doctor?.gender }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Address</td>
              <td>{{ doctor?.address }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Department</td>
              <td>{{ doctor?.department }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Clinic</td>
              <td>{{ doctor?.clinic }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Role</td>
              <td>{{ doctor?.role_name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div
      class="mt-md-3"
      v-show="
        user.role_id === 1 || (user.role_id === 3 && user.doctor.doctor_id === doctor.doctor_id)
      "
    >
      <button
        class="btn px-3 actionBtn d-block mx-auto"
        alt="view all patient"
        @click="getPatients(doctor.doctor_id, doctor.name)"
      >
        View All Patients
      </button>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const doctor = ref({})
const user = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
onMounted(async () => {
  await getDoctor()
  await getUser()
})
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
const getPatients = (param, name) => {
  router.push({
    path: `/doctors/${param}/patients`,
    params: { param: param, name: name },
    query: { name: name }
  })
}
const getDoctor = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/doctors/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      console.log('here >>> here', data.data)
      doctor.value = data.data
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const back = () => {
  router.push('/doctors')
}
</script>
<style scoped>
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css');

.backarrow {
  font-size: 30px;
  color: #343a40;
}
.backarrow:hover {
  color: #4da8f5;
}
#title {
  font-size: 32px;
  color: #343a40 !important;
}
table {
  color: #343a40 !important;
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
#btnDelete {
  background-color: #f08080;
  color: #343a40;
}
#btnDelete:hover {
  background-color: #f65454;
  color: #fff;
}
.btnBack:active {
  border: 2px solid #fff;
}
.trash {
  display: block;
  margin: auto;
  width: 100px;
  height: 100px;
}
</style>
