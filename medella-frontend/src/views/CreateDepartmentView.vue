<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-lg-1 col-md-2 col-sm-2 ms-md-2 ms-sm-0 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-7 col-sm-8 offset-lg-3 offset-md-1 offset-sm-0 col-10 mt-2">
        <span id="title" class="fw-medium mx-auto h3">New Department Creation</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Department Created Successfully.</strong>
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
          <form class="" @submit.prevent="createDepartment">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="name"
                v-model="department.name"
                required
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Enter Department Name*</label>
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
              <label for="email" class="form-label">Enter Department Email*</label>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="phone"
                placeholder="phone"
                v-model="department.phone"
                required
                aria-describedby="phoneHelp"
              />
              <label for="phone" class="form-label">Enter Department Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="description"
                placeholder="description"
                v-model="department.description"
                aria-describedby="descriptionHelp"
              />
              <label for="description" class="form-label"
                >Enter Description <span class="optionalText">(optional)</span></label
              >
            </div>

            <div class="d-block"></div>
            <button type="submit" class="btn btnCreate">Create department</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import router from '@/router'

const department = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])

const back = () => {
  router.push('/departments')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}

const createDepartment = async () => {
  showAlert.value = false
  showError.value = false
  try {
    const formData = new FormData()
    formData.append('name', department.value.name)
    formData.append('email', department.value.email)
    formData.append('phone', department.value.phone)
    formData.append('description', department.value.phone)

    const response = await fetch('http://127.0.0.1:8000/api/departments', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })

    const data = await response.json()
    console.log('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>', data.data)
    if (response.ok) {
      showAlert.value = true
      department.value.name = ''
      department.value.email = ''
      department.value.phone = ''
      department.value.description = ''

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
.optionalText {
  font-size: 1rem;
  color: #b0bec5;
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
.btnCreate {
  background-color: #4da8f5;
  display: block;
  margin: auto;
  color: #fff !important;
}

* {
  color: #343a40 !important;
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
