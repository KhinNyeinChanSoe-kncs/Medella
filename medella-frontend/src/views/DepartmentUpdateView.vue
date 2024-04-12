<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-lg-1 col-md-2 col-sm-2 ms-md-2 ms-sm-0 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-8 col-sm-8 offset-lg-3 offset-md-0 offset-sm-0 col-10">
        <span id="title" class="fw-medium mx-auto">Department Update</span>
      </div>
    </div>
    <div class="row container">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Department Updated Successfully.</strong>
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
          <form class="" @submit.prevent="updateDepartment">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="id"
                placeholder="id"
                v-model="department.id"
                aria-describedby="idHelp"
                disabled
              />
              <label for="id" class="form-label">Department ID</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                required
                placeholder="name"
                v-model="department.name"
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Name*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="email"
                class="form-control form-control-sm"
                placeholder="email"
                v-model="department.email"
                id="email"
                required
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
                v-model="department.phone"
                aria-describedby="phoneHelp"
              />
              <label for="phone" class="form-label">Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                placeholder="city"
                v-model="department.description"
                id="city"
                aria-describedby="cityHelp"
              />
              <label for="city" class="form-label"
                >Description<span class="optionalText ms-2">(optional)</span></label
              >
            </div>
            <div class="d-block"></div>
            <button type="submit" class="btn btnUpdate">Update Department</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'

const department = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
onMounted(async () => {
  await getDepartment()
})

const getDepartment = async () => {
  try {
    const id = router.currentRoute.value.params.id
    console.log('ID>>>>>>>>>>>>', id)
    const response = await fetch(`http://127.0.0.1:8000/api/departments/${id}`, {
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
      department.value = data.data
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const updateDepartment = async () => {
  const updateData = new URLSearchParams()
  updateData.append('name', department.value.name)
  updateData.append('email', department.value.email)
  updateData.append('phone', department.value.phone)
  updateData.append('description', department.value.description)

  console.log(updateData)

  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/departments/${id}`, {
      method: 'PUT',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`,
        'content-type': 'application/x-www-form-urlencoded'
      },
      body: updateData.toString()
    })
    const data = await response.json()
    if (response.ok) {
      showAlert.value = true
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
  router.push('/departments')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
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
.optionalText {
  font-size: 1rem;
  color: #b0bec5;
}
.btnUpdate {
  background-color: #4da8f5;
  display: block;
  margin: auto;
  color: #fff;
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
