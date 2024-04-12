<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-md-1 ms-md-2 col-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-6 col-sm-6 offset-md-3 col-10 mt-2">
        <span id="title" class="fw-medium mx-auto h3">Clinic Update</span>
      </div>
    </div>
    <div class="row container">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Clinic Updated Successfully.</strong>
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
          <form class="" @submit.prevent="updateClinic">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="id"
                placeholder="id"
                v-model="clinic.id"
                aria-describedby="idHelp"
                disabled
              />
              <label for="id" class="form-label">Clinic ID</label>
            </div>
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
              <label for="name" class="form-label">Clinic Name*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="email"
                class="form-control form-control-sm"
                placeholder="email"
                v-model="clinic.email"
                id="email"
                required
                aria-describedby="emailHelp"
              />
              <label for="email" class="form-label">Clinic Email*</label>
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
              <label for="phone" class="form-label">Clinic Phone*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                placeholder="city"
                v-model="clinic.description"
                id="city"
                aria-describedby="cityHelp"
              />
              <label for="city" class="form-label"
                >Description <span class="optionalText">(optional)</span></label
              >
            </div>
            <div class="d-block"></div>
            <button type="submit" class="btn btnUpdate">Update Clinic</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'

const clinic = ref({})
const storedToken = ref(localStorage.getItem('accessToken'))
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
onMounted(async () => {
  await getClinic()
})

const getClinic = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/clinics/${id}`, {
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
      clinic.value = data.data
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const updateClinic = async () => {
  const updateData = new URLSearchParams()
  updateData.append('name', clinic.value.name)
  updateData.append('email', clinic.value.email)
  updateData.append('phone', clinic.value.phone)
  updateData.append('description', clinic.value.description)

  console.log(updateData)

  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/clinics/${id}`, {
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
      clinic.value.name = ''
      clinic.value.email = ''

      clinic.value.phone = ''
      clinic.value.description = ''
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
  router.push('/clinics')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
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
#title {
  /* font-size: 32px; */
  color: #343a40 !important;
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
