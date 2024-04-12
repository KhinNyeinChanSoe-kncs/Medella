<template>
  <div class="container">
    <!-- alert -->
    <div class="row mt-3">
      <div class="col-md-1 ms-md-2">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow"></i>
        </button>
      </div>
      <div class="col-md-6 offset-md-3">
        <span id="title" class="fw-medium mx-auto">Update Patient Information</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Patient Information Updated Successfully.</strong>
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
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a
                class="nav-link"
                aria-current="page"
                @click="handleForm(1)"
                :class="showFormOne ? 'active' : ''"
                :style="{ color: showFormOne ? '#4da8f5' : '#343a40' }"
                >1</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                @click="handleForm(2)"
                :class="showFormTwo ? 'active' : ''"
                :style="{ color: showFormTwo ? '#4da8f5' : '#343a40' }"
                >2</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                @click="handleForm(3)"
                :class="showFormThree ? 'active' : ''"
                :style="{ color: showFormThree ? '#4da8f5' : '#343a40' }"
                >3</a
              >
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                @click="handleForm(4)"
                :class="showFormFour ? 'active' : ''"
                :style="{ color: showFormFour ? '#4da8f5' : '#343a40' }"
                >4</a
              >
            </li>
          </ul>
          <form action="" class="mt-md-2" @submit.prevent="updatePatient">
            <div class="tab-content" :style="{ display: showFormOne ? 'block' : 'none' }" id="fOne">
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  placeholder="name"
                  required
                  v-model="patient.user_name"
                  aria-describedby="nameHelp"
                />
                <label for="name" class="form-label">Enter Name*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="email"
                  class="form-control"
                  id="email"
                  placeholder="email"
                  required
                  v-model="patient.user_email"
                  aria-describedby="emailHelp"
                />
                <label for="email" class="form-label">Enter Email*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="phone"
                  placeholder="phone"
                  required
                  v-model="patient.user_phone"
                  aria-describedby="phoneHelp"
                />
                <label for="phone" class="form-label">Enter Phone*</label>
              </div>
              <div
                class="mb-3"
                style="
                  position: relative;
                  border: 1px solid #e2e5e9;
                  border-radius: 5px;
                  padding: 10px;
                "
              >
                <input
                  ref="fileInput"
                  class="form-control"
                  type="file"
                  id="formFile"
                  @change="handleFileChange"
                  style="opacity: 0; z-index: 1"
                />
                <div
                  v-if="selectedFileName"
                  style="
                    position: absolute;
                    top: 50%;
                    left: 10px;
                    right: 10px;
                    transform: translateY(-50%);
                    z-index: 0;
                    pointer-events: none;
                  "
                >
                  {{ selectedFileName }}
                </div>
                <div
                  v-else
                  style="
                    position: absolute;
                    top: 50%;
                    left: 10px;
                    right: 10px;
                    transform: translateY(-50%);
                    z-index: 0;
                    pointer-events: none;
                    color: #343a40;
                  "
                >
                  Choose avatar
                </div>
              </div>
              <div class="form-floating mb-3">
                <textarea
                  class="form-control form-control-sm"
                  placeholder="Address"
                  id="address"
                  style="height: 80px"
                  v-model="patient.user_address"
                  required
                ></textarea>
                <label for="address">Enter Address*</label>
              </div>

              <div class="d-flex justify-content-center text-center">
                <button type="button" class="btn d-block btnNextControl" @click="handleForm(2)">
                  Next <img src="./../assets/arrow-small-right.svg" alt="" />
                </button>
              </div>
            </div>
            <div class="tab-content" :style="{ display: showFormTwo ? 'block' : 'none' }" id="fTwo">
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  placeholder="city"
                  v-model="patient.user_city"
                  required
                  id="city"
                  aria-describedby="cityHelp"
                />
                <label for="city" class="form-label">Enter City*</label>
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
              <div class="form-floating mb-3">
                <textarea
                  class="form-control form-control-sm"
                  placeholder="allergyReactions"
                  v-model="patient.allergies_reactions"
                  id="allergyReactions"
                  style="height: 80px"
                ></textarea>
                <label for="allergyReactions"
                  >Enter Allergies Reactions<span class="optionalText ms-2">(optional)</span></label
                >
              </div>
              <div class="form-floating mb-3">
                <textarea
                  class="form-control form-control-sm"
                  placeholder="sugeryHistories"
                  v-model="patient.surgery_histories"
                  id="sugeryHistories"
                  style="height: 80px"
                ></textarea>
                <label for="sugeryHistories"
                  >Enter Surgery Histories<span class="optionalText ms-2">(optional)</span></label
                >
              </div>
              <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping">Enter Date of Birth*</span>
                <input
                  type="date"
                  class="form-control py-2"
                  placeholder="DOB"
                  aria-label="date of Birth"
                  required
                  v-model="patient.dob"
                  aria-describedby="addon-wrapping"
                />
              </div>
              <div class="d-flex justify-content-center text-center">
                <button type="button" class="btn d-block me-2 btnControl" @click="handleForm(1)">
                  <img src="./../assets/arrow-small-left.svg" class="me-2" alt="" />Previous</button
                ><button type="button" class="btn d-block btnNextControl" @click="handleForm(3)">
                  Next <img src="./../assets/arrow-small-right.svg" alt="" />
                </button>
              </div>
            </div>
            <div
              class="tab-content"
              :style="{ display: showFormThree ? 'block' : 'none' }"
              id="fThree"
            >
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="parentName"
                  placeholder="parentName"
                  required
                  v-model="patient.parent_name"
                  aria-describedby="parentNameHelp"
                />
                <label for="parentName" class="form-label">Enter Parent Name*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="emergenctyContactNumber"
                  placeholder="emergenctyContactNumber"
                  required
                  v-model="patient.emergency_contact_number"
                  aria-describedby="emergenctyContactNumberHelp"
                />
                <label for="emergenctyContactNumber" class="form-label"
                  >Enter Emergency Contact Number*</label
                >
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="bloodType"
                  placeholder="bloodType"
                  required
                  v-model="patient.blood_type"
                  aria-describedby="bloodTypeHelp"
                />
                <label for="bloodType" class="form-label">Enter Blood Type*</label>
              </div>
              <div class="mb-3 form-control">
                <label for="bornStatus" class="form-label"
                  >Enter Born Status <span class="optionalText">(optional)</span></label
                >
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    v-model="patient.born_status"
                    id="bornStatus"
                    :checked="patient.born_status === 1"
                  />
                  <label class="form-check-label" for="bornStatus">Born Status</label>
                </div>
              </div>

              <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping"
                  >Enter Born Date <span class="optionalText ms-2">(optional)</span></span
                >
                <input
                  type="date"
                  class="form-control py-2"
                  placeholder="Born Date"
                  aria-label="borndate"
                  v-model="patient.born_date"
                  aria-describedby="addon-wrapping"
                />
              </div>
              <div class="d-flex justify-content-center text-center">
                <button type="button" class="btn d-block btnControl me-2" @click="handleForm(2)">
                  <img src="./../assets/arrow-small-left.svg" class="me-2" alt="" />Previous
                </button>
                <button type="button" class="btn d-block btnNextControl" @click="handleForm(4)">
                  Next <img src="./../assets/arrow-small-right.svg" alt="" />
                </button>
              </div>
            </div>
            <div
              class="tab-content"
              :style="{ display: showFormFour ? 'block' : 'none' }"
              id="fThree"
            >
              <div class="mb-3 form-control">
                <label for="deadStatus" class="form-label"
                  >Enter Dead Status <span class="optionalText">(optional)</span></label
                >
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    role="switch"
                    v-model="patient.dead_status"
                    id="deadStatus"
                    :checked="patient.dead_status === 1"
                  />
                  <label class="form-check-label" for="deadStatus">Dead Status</label>
                </div>
              </div>

              <div class="input-group flex-nowrap mb-3">
                <span class="input-group-text" id="addon-wrapping"
                  >Enter Death Date <span class="optionalText ms-2">(optional)</span></span
                >
                <input
                  type="date"
                  class="form-control py-2"
                  placeholder="Death Date"
                  aria-label="death date"
                  v-model="patient.dead_date"
                  aria-describedby="addon-wrapping"
                />
              </div>
              <div class="d-flex justify-content-center text-center">
                <button type="button" class="btn d-block btnControl me-2" @click="handleForm(3)">
                  <img src="./../assets/arrow-small-left.svg" class="me-2" alt="" />Previous
                </button>
                <button class="btn btnUpdate d-block">Update Patient</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import router from '@/router'

const storedToken = ref(localStorage.getItem('accessToken'))
const selectedGender = ref('')
const fileInput = ref(null)
const selectedFileName = ref('')
const patient = ref({})
const patient_id = ref('')
onMounted(async () => {
  patient_id.value = router.currentRoute.value.params.id
  getPatinetInfo()
})

const handleFileChange = () => {
  const file = fileInput.value.files[0]
  if (file) {
    selectedFileName.value = file.name
  } else {
    selectedFileName.value = ''
  }
}
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
const showFormOne = ref(true)
const showFormTwo = ref(false)
const showFormThree = ref(false)
const showFormFour = ref(false)
const back = () => {
  router.push(`/patients/${patient_id.value}`)
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
const getPatinetInfo = async () => {
  try {
    const response = await fetch(`http://127.0.0.1:8000/api/patients/${patient_id.value}`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      console.log('response data', data.data)
      patient.value = data.data
      selectedFileName.value = data.data.user_avatar
      selectedGender.value = data.data.user_gender
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const updatePatient = async () => {
  try {
    const updateData = new FormData()
    updateData.append('name', patient.value.user_name)
    updateData.append('email', patient.value.user_email)
    updateData.append('address', patient.value.user_address)
    updateData.append('phone', patient.value.user_phone)
    updateData.append('city', patient.value.user_city)
    updateData.append('allergies_reactions', patient.value.allergies_reactions)
    updateData.append('surgery_histories', patient.value.surgery_histories)
    updateData.append('dob', patient.value.dob)
    updateData.append('emergency_contact_number', patient.value.emergency_contact_number)
    updateData.append('parent_name', patient.value.parent_name)
    updateData.append('blood_type', patient.value.blood_type)
    updateData.append('gender', selectedGender.value)

    if (selectedFileName.value !== patient.value.user_avatar && fileInput.value.files[0]) {
      updateData.append('avatar', fileInput.value.files[0])
    }
    if (typeof patient.value.born_status !== 'undefined' && patient.value.born_status !== null) {
      if (patient.value.born_status) {
        updateData.append('born_status', 1)
      } else {
        updateData.append('born_status', 0)
      }
    }

    if (typeof patient.value.born_date !== 'undefined' && patient.value.born_date !== null) {
      console.log('here', patient.value.born_date)
      updateData.append('born_date', patient.value.born_date)
    }
    if (typeof patient.value.dead_status !== 'undefined' && patient.value.dead_status !== null) {
      if (patient.value.dead_status) {
        updateData.append('dead_status', 1)
      } else {
        updateData.append('dead_status', 0)
      }
    }

    if (typeof patient.value.dead_date !== 'undefined' && patient.value.dead_date !== null) {
      console.log('here', patient.value.dead_date)
      updateData.append('dead_date', patient.value.dead_date)
    }

    const response = await fetch(
      `http://127.0.0.1:8000/api/patients/info/update/${patient_id.value}`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        },
        body: updateData
      }
    )
    const data = await response.json()
    if (response.ok) {
      console.log('Response Data', data)
      showAlert.value = true
    } else {
      console.log('ERROR:', data)
      showError.value = true
      error_msg.value = data.message
    }
  } catch (e) {
    console.log(e)
  }
}

const handleForm = (form_number) => {
  switch (form_number) {
    case 1:
      showFormOne.value = true
      showFormTwo.value = false
      showFormThree.value = false
      showFormFour.value = false
      break
    case 2:
      showFormOne.value = false
      showFormTwo.value = true
      showFormThree.value = false
      showFormFour.value = false
      break
    case 3:
      showFormOne.value = false
      showFormTwo.value = false
      showFormThree.value = true
      showFormFour.value = false
      break
    case 4:
      showFormOne.value = false
      showFormTwo.value = false
      showFormThree.value = false
      showFormFour.value = true
      break
  }
}
</script>
<style scoped>
@import url('https://cdn-uicons.flaticon.com/2.2.0/uicons-regular-rounded/css/uicons-regular-rounded.css');
.optionalText {
  font-size: 1rem;
  color: #b0bec5;
}
.btnControl {
  border: 2px solid #c9e3f9;
}

.btnControl:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnControl:focus {
  background-color: #74b9f3;
  cursor: pointer;
}
.btnControl:active {
  border: 2px solid #74b9f3;
}
@keyframes float {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
  100% {
    transform: translateY(0);
  }
}

.upload {
  animation: float 3s ease-in-out infinite;
}

.nav-item a {
  color: #343a40;
}
.nav-item a:hover {
  color: #4da8f5;
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
  font-size: 32px;
  color: #343a40 !important;
}
.alert-success {
  background-color: #4da7f535;
  color: #343a40;
}
.alert-error {
  background-color: #f3909078;
  color: #343a40;
}

.btnUpdate {
  color: #fff;
  background-color: #4da8f5;
}

.btnUpdate:hover {
  background-color: #74b9f3;
  cursor: pointer;
}
.btnUpdate:active {
  border: 2px solid #74b9f3;
}
.btnNextControl {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}
.btnNextControl:hover,
.btnNextControl:focus {
  background-color: #74b9f3;
}
</style>
