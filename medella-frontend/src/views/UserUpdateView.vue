<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-lg-1 col-md-2 col-sm-2 ms-md-2 ms-sm-0 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-7 col-sm-8 offset-lg-3 offset-md-1 offset-sm-0 col-10">
        <span id="title" class="fw-medium mx-auto">Update User Info</span>
      </div>
    </div>
    <div class="row container">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>User Updated Successfully.</strong>
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
          <form class="" @submit.prevent="updateUser">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="id"
                placeholder="id"
                v-model="user.user_id"
                aria-describedby="idHelp"
                disabled
              />
              <label for="id" class="form-label">User ID</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="name"
                required
                v-model="user.user_name"
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Name*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="email"
                class="form-control form-control-sm"
                placeholder="email"
                v-model="user.user_email"
                required
                id="email"
                aria-describedby="emailHelp"
              />
              <label for="email" class="form-label">Email*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="phone"
                required
                placeholder="phone"
                v-model="user.user_phone"
                aria-describedby="phoneHelp"
              />
              <label for="phone" class="form-label">Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                placeholder="city"
                v-model="user.user_city"
                id="city"
                required
                aria-describedby="cityHelp"
              />
              <label for="city" class="form-label">City*</label>
            </div>
            <div class="form-floating mb-3">
              <textarea
                class="form-control form-control-sm"
                placeholder="Address"
                v-model="user.user_address"
                id="address"
                required
                style="height: 100px"
              ></textarea>
              <label for="address">Address</label>
            </div>
            <select
              v-model="user.user_gender"
              class="form-select mb-3 form-floating"
              aria-label="Default select example"
              required
            >
              <option class="mb-md-1" disabled selected>Choose Gender*</option>
              <option class="mb-md-1" value="male">Male</option>
              <option class="mb-md-1" value="female">Female</option>
            </select>
            <select
              v-model="selectedRoleId"
              class="form-select mb-3 form-floating"
              aria-label="Default select example"
              required
            >
              <option class="mb-md-1" disabled value="">Choose Role*</option>
              <option class="mb-md-1" v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>

            <div class="d-block"></div>
            <button type="submit" class="btn btnUpdate">Update User</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'

const user = ref({})
const selectedRoleId = ref('')
const storedToken = ref(localStorage.getItem('accessToken'))
const roles = ref([])

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
onMounted(async () => {
  await getUser()
  await getRoles()
})
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
const getRoles = async () => {
  const response = await fetch('http://127.0.0.1:8000/api/roles', {
    method: 'GET',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${storedToken.value}`
    }
  })
  const data = await response.json()
  roles.value = data.data
  console.log(data.data)
}
const getUser = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      console.log(data.data)
      user.value = data.data
      selectedRoleId.value = data.data.role_id
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const updateUser = async () => {
  const updateData = new URLSearchParams()
  updateData.append('name', user.value.user_name)
  updateData.append('email', user.value.user_email)
  updateData.append('phone', user.value.user_phone)
  updateData.append('city', user.value.user_city)
  updateData.append('address', user.value.user_address)
  updateData.append('gender', user.value.user_gender)
  updateData.append('role_id', selectedRoleId.value)
  console.log(updateData)

  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
      method: 'PUT',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`,
        'content-type': 'application/x-www-form-urlencoded'
      },
      body: updateData.toString()
    })
    const data = await response.json()
    console.log(data)
    if (response.ok) {
      showAlert.value = true

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
.btnBack:active {
  border: 2px solid #fff;
}
#title {
  font-size: 32px;
  color: #343a40 !important;
}

.btnUpdate {
  background-color: #4da8f5;
  color: #fff;
  display: block;
  margin: auto;
}

.btnUpdate:hover {
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
