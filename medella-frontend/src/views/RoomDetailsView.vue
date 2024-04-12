<template>
  <div class="container">
    <!-- Modal -->
    <div
      class="modal fade"
      id="reserveModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="reserveModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div class="fw-medium reserveTitle h4 text-center mb-3">Reserve Room</div>

            <div class="">
              <div
                v-if="showAlert"
                class="alert alert-success alert-dismissible fade show mt-md-5"
                role="alert"
              >
                <strong>Room Reserved Successfully</strong>
                <button
                  type="button"
                  class="btn-close"
                  @click="hideAlert"
                  aria-label="Close"
                ></button>
              </div>
              <div
                v-if="showError"
                class="alert alert-error alert-dismissible fade show error mt-md-5"
                role="alert"
              >
                <strong>{{ error_msg }}</strong>
                <button
                  type="button"
                  class="btn-close"
                  @click="hideError"
                  aria-label="Close"
                ></button>
              </div>
              <form action="">
                <div class="mb-3 form-floating">
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    required
                    placeholder="name"
                    v-model="patient_id"
                    aria-describedby="patientIDHelp"
                  />
                  <label for="name" class="form-label">Enter Patient ID*</label>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              Cancel
            </button>
            <button type="button" class="btn btnModalReserve" @click="reserveRoom">
              Reserve Room
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-2 ms-md-2 ms-sm-0">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-5 offset-lg-3 offset-md-2 offset-sm-0 col-10">
        <span id="title" class="fw-medium mx-auto">Room Info</span>
      </div>
      <div class="col-lg-1 col-md-2 col-sm-5 col-2 offset-md-2 mt-2">
        <button
          class="btn btnUpdate btn-sm"
          @click="updateRoom(room.id)"
          v-show="user.role_id === 1"
        >
          <img src="./../assets/pencil.svg" alt="" />
        </button>
      </div>
    </div>
    <div class="row mt-md-5">
      <div class="col-8 offset-2">
        <table class="table table-borderless">
          <tbody>
            <tr>
              <td class="fw-medium">ID</td>
              <td>{{ room.id }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Room Number</td>
              <td>{{ room.room_number }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Room Type</td>
              <td>{{ room.room_type }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Floor Number</td>
              <td>{{ room.floor_number }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Available Status</td>
              <td>{{ room_status }}</td>
            </tr>
            <tr>
              <td class="fw-medium">Available Bed</td>
              <td>{{ room.available_bed }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="mt-md-3">
      <button
        class="btn d-block mx-auto btnReserve"
        :disabled="!room.status"
        data-bs-toggle="modal"
        data-bs-target="#reserveModal"
      >
        Reserve Room
      </button>
    </div>
    <!-- <div class="container mt-md-5">
      <div
        v-if="showAlert"
        class="alert alert-success alert-dismissible fade show mt-md-5"
        role="alert"
      >
        <strong>Room Reserved Successfully</strong>
        <button type="button" class="btn-close" @click="hideAlert" aria-label="Close"></button>
      </div>
      <div
        v-if="showError"
        class="alert alert-error alert-dismissible fade show error mt-md-5"
        role="alert"
      >
        <strong>{{ error_msg }}</strong>
        <button type="button" class="btn-close" @click="hideError" aria-label="Close"></button>
      </div>
    </div> -->
  </div>
</template>
<script setup>
import router from '@/router'
import { ref, onMounted, onUpdated } from 'vue'

const room = ref({})
const room_status = ref('')
const patient_id = ref('')
const storedToken = ref(localStorage.getItem('accessToken'))
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
const user = ref({})
onMounted(async () => {
  await getRoom()
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
onUpdated(async () => {
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
      room.value = data.data
      if (data.data.status == 1) {
        room_status.value = 'Available'
      } else {
        room_status.value = 'Not Available'
      }
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const back = () => {
  router.push('/rooms')
}
const updateRoom = (param) => {
  router.push(`/rooms/${param}/update`)
}
const reserveRoom = async () => {
  const id = router.currentRoute.value.params.id
  showAlert.value = false
  showError.value = false
  try {
    const formData = new FormData()
    formData.append('patient_id', patient_id.value)
    const response = await fetch(`http://127.0.0.1:8000/api/reserve/rooms/${id}`, {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })

    const data = await response.json()
    console.log('FUCKING DATA :', data)
    if (response.ok) {
      // showAlert.value = true
      handleAlert(true, data)
      patient_id.value = ''
    } else {
      console.log('Response Not OK', data)
      handleAlert(false, data)
    }
  } catch (e) {
    console.log('Error:' + e)
  }
}
const handleAlert = (status, data) => {
  if (status == true) {
    showAlert.value = true
  } else {
    showError.value = true
    error_msg.value = data.message
    console.log('Error: ' + error_msg.value)
  }
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
</script>
<style scoped>
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css');

.reserveTitle {
}
.btnUpdate {
  border: 2px solid #c9e3f9;
}

.btnUpdate:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnUpdate:active {
  border: 2px solid #74b9f3;
}
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
.btn-edit {
  border: #4da8f5;
}
.btn-edit {
  border: 2px solid #74b9f3;
}

.btn-edit:hover {
  background-color: #c9e3f9;
  cursor: pointer;
}

.btnBack:active {
  border: 2px solid #fff;
}

.btnReserve {
  background-color: #4da8f5;
  color: #fff;
}

.btnReserve:hover {
  background-color: #74b9f3;
}

.btnReserve:active {
  border: 2px solid #74b9f3;
  /* background-color: #74b9f3; */
}
.btnReserve:disabled {
  opacity: 0.3;
  cursor: not-allowed;
}
.btnModalReserve {
  color: #fff;
  background-color: #4da8f5;
}
.btnModalReserve:hover {
  color: #fff;
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
