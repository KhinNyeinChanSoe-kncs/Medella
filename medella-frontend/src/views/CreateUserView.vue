<template>
  <div class="container">
    <div class="row mt-3 mb-3">
      <div class="col-lg-1 col-md-2 col-sm-2 ms-md-2 ms-sm-0 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-8 col-sm-8 offset-lg-3 offset-md-1 offset-sm-0 col-10">
        <span id="title" class="fw-medium mx-auto">New User Creation</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>User Created Successfully.</strong>
        <button type="button" class="btn-close" @click="hideAlert" aria-label="Close"></button>
      </div>
      <div
        v-if="showError"
        class="alert alert-error alert-dismissible fade show error"
        role="alert"
      >
        <strong>{{ error_msg }}</strong>
        <button type="button" class="btn-close" @click="hideError" aria-label="Close"></button>
      </div>
      <div class="card">
        <div class="card-body">
          <form class="" @submit.prevent="createUser">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="name"
                v-model="user.name"
                required
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Enter Name*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="email"
                class="form-control form-control-sm"
                placeholder="email"
                v-model="user.email"
                id="email"
                required
                aria-describedby="emailHelp"
              />
              <label for="email" class="form-label">Enter Email*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="password"
                class="form-control form-control-sm"
                alt="password input"
                placeholder="password"
                id="password"
                v-model="user.password"
                required
              />
              <label for="password" class="form-label" alt="enter password">Enter Password*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="phone"
                placeholder="phone"
                v-model="user.phone"
                required
                aria-describedby="phoneHelp"
              />
              <label for="phone" class="form-label">Enter Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                placeholder="city"
                v-model="user.city"
                required
                id="city"
                aria-describedby="cityHelp"
              />
              <label for="city" class="form-label">Enter City*</label>
            </div>
            <div class="form-floating mb-3">
              <textarea
                class="form-control form-control-sm"
                placeholder="Address"
                v-model="user.address"
                id="address"
                style="height: 80px"
                required
              ></textarea>
              <label for="address">Enter Address*</label>
            </div>
            <select
              class="form-select mb-3 form-floating"
              aria-label="Default select example"
              v-model="selectedGender"
              required
            >
              <option class="mb-md-1" disabled selected value="">Choose Gender*</option>
              <option class="mb-md-1" value="male">Male</option>
              <option class="mb-md-1" value="female">Female</option>
            </select>
            <select
              v-model="selectedRoleId"
              class="form-select mb-3 form-floating"
              required
              aria-label="Default select example"
            >
              <option class="mb-md-1" disabled value="">Choose Role*</option>
              <option class="mb-md-1" v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
            <div class="mb-3">
              <span class="fw-lighter text-secondary ms-2" style="font-size: 14px"
                >Only choose clinic the user is medical staff or doctor</span
              >
              <select
                v-model="selectedClinicId"
                class="form-select form-floating"
                aria-label="Default select example"
              >
                <option class="mb-md-1" disabled value="">
                  Choose Clinic <span class="optionalText">(optional)</span>
                </option>

                <option
                  class="mb-md-1"
                  v-for="clinic in clinics"
                  :key="clinic.id"
                  :value="clinic.id"
                >
                  {{ clinic.name }}
                </option>
              </select>
            </div>
            <select
              v-model="selectedDepartmentId"
              class="form-select mb-5 form-floating"
              required
              aria-label="Default select example"
            >
              <option class="mb-md-1" disabled value="">Choose Department*</option>
              <option class="mb-md-1" v-for="dept in depts" :key="dept.id" :value="dept.id">
                {{ dept.name }}
              </option>
            </select>

            <div class="d-block"></div>
            <button type="submit" class="btn btnCreate">Create User</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import router from '@/router'

const user = ref({})
const selectedGender = ref('')
const depts = ref([])
const clinics = ref([])
const roles = ref([])
const selectedDepartmentId = ref('')
const selectedClinicId = ref('')
const selectedRoleId = ref('')
const storedToken = ref(localStorage.getItem('accessToken'))

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
onMounted(async () => {
  await getDepts()
  await getRoles()
  await getClinics()
})

const back = () => {
  router.push('/users')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
const getRoles = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/roles', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    roles.value = data.data
    roles.value.pop()
    console.log(data.data)
  } catch (e) {
    console.log(e)
  }
}

const getDepts = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/departments', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    depts.value = data.data
    console.log(data.data)
  } catch (e) {
    console.log(e)
  }
}
const getClinics = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/clinics', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    clinics.value = data.data
    console.log(data.data)
  } catch (e) {
    console.log(e)
  }
}

const createUser = async () => {
  showAlert.value = false
  showError.value = false
  try {
    const userData = new FormData()
    userData.append('name', user.value.name)
    userData.append('email', user.value.email)
    userData.append('phone', user.value.phone)
    userData.append('password', user.value.password)
    userData.append('city', user.value.city)
    userData.append('address', user.value.address)
    userData.append('gender', selectedGender.value)
    userData.append('department_id', selectedDepartmentId.value)
    userData.append('role_id', selectedRoleId.value)

    if (selectedRoleId.value == 2 || selectedRoleId.value == 3) {
      userData.append('clinic_id', selectedClinicId.value)
    }

    const response = await fetch('http://127.0.0.1:8000/api/users', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: userData
    })

    const data = await response.json()
    console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', data.data)
    if (response.ok) {
      showAlert.value = true
      user.value.name = ''
      user.value.email = ''
      user.value.password = ''
      user.value.phone = ''
      user.value.city = ''
      user.value.address = ''
      selectedDepartmentId.value = ''
      selectedClinicId.value = ''
      selectedGender.value = ''
      selectedRoleId.value = ''
      // router.back()
    } else {
      console.log(data)
      showError.value = true
      error_msg.value = data.message
    }
  } catch (e) {
    console.log(e)
  }
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
.btnBack:active {
  border: 2px solid #fff;
}
#title {
  font-size: 32px;
  color: #343a40 !important;
}
.btnCreate {
  background-color: #4da8f5;
  display: block;
  margin: auto;
  color: #fff !important;
}
.optionalText {
  font-size: 1rem;
  color: #b0bec5 !important;
}
.btnCreate:hover {
  background-color: #74b9f3;
  cursor: pointer;
}

.alert-success {
  background-color: #4da7f535;
  color: #343a40;
}
.alert-error {
  background-color: #f3909078;
  color: #343a40;
}
</style>
