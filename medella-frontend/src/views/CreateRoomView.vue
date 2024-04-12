<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-md-1 ms-md-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-4 offset-md-3">
        <span id="title" class="fw-medium mx-auto">New Room Creation</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Room Created Successfully.</strong>
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
          <form class="" @submit.prevent="createRoom">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="room_number"
                placeholder="room_number"
                v-model="room.room_number"
                required
                aria-describedby="room_numberHelp"
              />
              <label for="room_number" class="form-label">Enter Room Number*</label>
            </div>
            <div class="mb-3">
              <select
                class="form-select py-3 form-floating"
                v-model="selectedRoomType"
                aria-label="Default select example"
              >
                <option class="mb-md-1" disabled value="">Choose Room Type*</option>
                <option class="mb-md-1" value="private">Private Room</option>
                <option class="mb-md-1" value="semi-private">Semi-Private Room</option>
                <option class="mb-md-1" value="standard">Standard Room</option>
              </select>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="floor_number"
                placeholder="floor_number"
                v-model="room.floor_number"
                required
                aria-describedby="floor_numberHelp"
              />
              <label for="floor_number" class="form-label">Enter Floor Number*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="description"
                placeholder="description"
                v-model="room.charges"
                required
                aria-describedby="descriptionHelp"
              />
              <label for="description" class="form-label">Enter Charges*</label>
            </div>

            <div class="d-block"></div>
            <button type="submit" class="btn btnCreate">Create Room</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import router from '@/router'

const room = ref({})
const selectedRoomType = ref('')
const storedToken = ref(localStorage.getItem('accessToken'))

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])

const back = () => {
  router.push('rooms')
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}

const createRoom = async () => {
  showAlert.value = false
  showError.value = false
  try {
    const formData = new FormData()
    formData.append('room_number', room.value.room_number)
    formData.append('room_type', selectedRoomType.value)
    formData.append('floor_number', room.value.floor_number)
    formData.append('charges', room.value.charges)

    const response = await fetch('http://127.0.0.1:8000/api/rooms', {
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
      room.value.room_number = ''
      room.value.room_type = ''
      room.value.floor_number = ''
      room.value.charges = ''

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
  color: #fff;
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
