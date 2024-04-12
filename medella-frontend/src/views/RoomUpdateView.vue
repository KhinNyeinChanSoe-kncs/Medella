<template>
  <div class="container">
    <div class="row mt-3">
      <div class="col-md-1 ms-md-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-4 offset-md-3">
        <span id="title" class="fw-medium mx-auto">Room Update</span>
      </div>
    </div>
    <div class="row container mt-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Room Information Updated Successfully.</strong>
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
          <form class="" @submit.prevent="updateRoom">
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="id"
                placeholder="id"
                v-model="room.id"
                aria-describedby="idHelp"
                disabled
              />
              <label for="id" class="form-label">Room ID</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control"
                id="name"
                placeholder="name"
                required
                v-model="room.room_number"
                aria-describedby="nameHelp"
              />
              <label for="name" class="form-label">Room Number*</label>
            </div>
            <select
              class="form-select mb-3 form-floating"
              aria-label="Room Type Select Option"
              v-model="selectedRoomType"
              required
            >
              <option class="mb-md-1" disabled selected value="">Choose Room Type*</option>
              <option class="mb-md-1" value="private">Private</option>
              <option class="mb-md-1" value="semi-private">Semi-Private</option>
              <option class="mb-md-1" value="standard">Standard</option>
            </select>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="floorNumber"
                placeholder="floorNumber"
                v-model="room.floor_number"
                aria-describedby="floorHelp"
              />
              <label for="floorNumber" class="form-label">Floor Number*</label>
            </div>
            <div class="mb-3 form-floating">
              <input
                type="text"
                class="form-control form-control-sm"
                id="charges"
                placeholder="charges"
                v-model="room.charges"
                aria-describedby="chargesHelp"
              />
              <label for="charges" class="form-label">Charges*</label>
            </div>
            <div class="d-block"></div>
            <button type="submit" class="btn btnUpdate">Update Room</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'

const room = ref({})

const selectedRoomType = ref()
const storedToken = ref(localStorage.getItem('accessToken'))
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
onMounted(async () => {
  await getRoom()
})

const getRoom = async () => {
  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/rooms/${id}`, {
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
      room.value = data.data
      selectedRoomType.value = data.data.room_type
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const updateRoom = async () => {
  showAlert.value = false
  showError.value = false
  const updateData = new URLSearchParams()
  updateData.append('room_number', room.value.room_number)
  updateData.append('room_type', selectedRoomType.value)
  updateData.append('floor_number', room.value.floor_number)
  updateData.append('charges', room.value.charges)

  console.log(updateData)

  try {
    const id = router.currentRoute.value.params.id
    const response = await fetch(`http://127.0.0.1:8000/api/rooms/${id}`, {
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
      room.value.name = ''
      room.value.email = ''

      room.value.phone = ''
      room.value.description = ''
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

.btnUpdate {
  background-color: #4da8f5;
  display: block;
  margin: auto;
  color: #fff;
  cursor: pointer;
}

.btnUpdate:hover {
  background-color: #74b9f3;
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
