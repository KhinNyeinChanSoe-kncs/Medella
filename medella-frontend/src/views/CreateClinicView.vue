<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-md-1 ms-md-2 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-6 col-sm-6 offset-md-3 col-10 mt-2">
        <span id="title" class="fw-medium mx-auto h3">New Clinic Creation</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Clinic Created Successfully.</strong>
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
          <form class="" @submit.prevent="createClinic">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="name"
                v-model="clinic.name"
                required
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Enter Clinic Name*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="email"
                class="form-control form-control-sm"
                placeholder="email"
                v-model="clinic.email"
                required
                id="email"
                aria-describedby="emailHelp"
              />
              <label for="email" class="form-label">Enter Clinic Email*</label>
            </div>

            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="phone"
                placeholder="phone"
                v-model="clinic.phone"
                required
                aria-describedby="phoneHelp"
              />
              <label for="phone" class="form-label">Enter Clinic Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="description"
                placeholder="description"
                v-model="clinic.description"
                aria-describedby="descriptionHelp"
              />
              <label for="description" class="form-label"
                >Enter Description <span class="optionalText">(optional)</span></label
              >
            </div>

            <div class="d-block"></div>
            <button type="submit" class="btn btnCreate">Create Clinic</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import router from '@/router'

const clinic = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])

const back = () => {
  router.push('clinics')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}

const createClinic = async () => {
  showAlert.value = false
  showError.value = false
  try {
    const formData = new FormData()
    formData.append('name', clinic.value.name)
    formData.append('email', clinic.value.email)
    formData.append('phone', clinic.value.phone)
    formData.append('description', clinic.value.phone)

    const response = await fetch('http://127.0.0.1:8000/api/clinics', {
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
      clinic.value.name = ''
      clinic.value.email = ''
      clinic.value.phone = ''
      clinic.value.description = ''

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
* {
  /* font-size: 32px; */
  color: #343a40 !important;
}
.btnCreate {
  background-color: #4da8f5;
  display: block;
  margin: auto;
  color: #fff !important;
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
