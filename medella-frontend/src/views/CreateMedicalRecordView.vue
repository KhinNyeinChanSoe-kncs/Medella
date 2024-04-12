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
        <span id="title" class="fw-medium mx-auto">New Medical Record Creation</span>
      </div>
    </div>
    <div class="row container mt-md-3">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Medical Record Added for Patient</strong>
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
          </ul>
          <form action="" class="mt-md-2" @submit.prevent="creatRecord">
            <div class="tab-content" :style="{ display: showFormOne ? 'block' : 'none' }" id="fOne">
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="bloodPressure"
                  placeholder="bloodPressure"
                  required
                  aria-describedby="bloodPressureHelp"
                  v-model="record.blood_pressure"
                />
                <label for="bloodPressure" class="form-label">Enter Blood Pressure*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="bloodGlucose"
                  class="form-control"
                  id="bloodGlucose"
                  placeholder="bloodGlucose"
                  required
                  aria-describedby="bloodGlucoseHelp"
                  v-model="record.blood_glucose"
                />
                <label for="bloodGlucose" class="form-label">Enter Blood Glucose*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="weight"
                  placeholder="weight"
                  required
                  aria-describedby="weightHelp"
                  v-model="record.weight"
                />
                <label for="weight" class="form-label">Enter Weight*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control"
                  id="heartRate"
                  placeholder="heartRate"
                  required
                  aria-describedby="heartRateHelp"
                  v-model="record.heart_rate"
                />
                <label for="heartRate" class="form-label">Enter Heart Rate*</label>
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
                  class="form-control"
                  id="temperature"
                  placeholder="temperature"
                  required
                  v-model="record.temperature"
                  aria-describedby="temperatureHelp"
                />
                <label for="temperature" class="form-label">Enter Temperature*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  placeholder="repositoryRate"
                  required
                  id="repositoryRate"
                  v-model="record.repository_rate"
                  aria-describedby="repositoryRateHelp"
                />
                <label for="repositoryRate" class="form-label">Enter Repository Rate*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  placeholder="oxygenLevel"
                  required
                  v-model="record.oxygen_level"
                  id="oxygenLevel"
                  aria-describedby="oxygenLevelHelp"
                />
                <label for="oxygenLevel" class="form-label">Enter Oxygen Level*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="text"
                  class="form-control form-control-sm"
                  placeholder="disease"
                  required
                  id="disease"
                  v-model="record.disease"
                  aria-describedby="diseaseHelp"
                />
                <label for="disease" class="form-label">Enter Disease*</label>
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
              <select
                class="form-select form-floating mb-3"
                v-model="selectedDoctor"
                required
                aria-label="Default select example"
              >
                <option class="mb-md-1" value="" disabled>Select Doctor*</option>
                <option
                  class="mb-md-1"
                  v-for="doctor in doctorList"
                  :key="doctor.id"
                  :value="doctor.doctor_id"
                >
                  {{ doctor.name }}
                </option>
              </select>
              <div class="form-floating mb-3">
                <textarea
                  class="form-control form-control-sm"
                  placeholder="prescription"
                  id="prescription"
                  style="height: 80px"
                  v-model="record.prescription"
                ></textarea>
                <label for="prescription"
                  >Enter Prescription<span class="optionalText">(optional)</span></label
                >
              </div>
              <div class="form-floating mb-3">
                <textarea
                  class="form-control form-control-sm"
                  placeholder="clinical_notes"
                  id="clinical_notes"
                  style="height: 80px"
                  v-model="record.clinical_notes"
                ></textarea>
                <label for="clinical_notes"
                  >Enter Clinical Notes<span class="optionalText">(optional)</span></label
                >
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
                  multiple
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
                  Choose File Record<span class="optionalText">(optional)</span>
                </div>
              </div>
              <div class="d-flex justify-content-center text-center">
                <button type="button" class="btn d-block btnControl me-2" @click="handleForm(2)">
                  <img src="./../assets/arrow-small-left.svg" class="me-2" alt="" />Previous
                </button>
                <button class="btn btnRegister d-block" @click="createRecord">Add Record</button>
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

const fileInput = ref(null)
const selectedFileName = ref('')
const record = ref({})
const patient_id = router.currentRoute.value.params.id

const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])

const showFormOne = ref(true)
const showFormTwo = ref(false)
const showFormThree = ref(false)
const fileArray = ref([])
const doctorList = ref([])
const selectedDoctor = ref('')
onMounted(async () => {
  await getDoctors()
  console.log(router.currentRoute.value.params.status)
})
const getDoctors = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/doctors', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      doctorList.value = data.data.data
      console.log('doctor list', doctorList.value)
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const handleFileChange = () => {
  const files = fileInput.value.files

  if (files.length > 0) {
    const fileArrayValue = Array.from(files)
    fileArray.value = fileArrayValue

    console.log('files>>>>>>>>>>>>>>>>>>>', Array.from(fileArray.value))

    const fileNames = fileArrayValue.map((file) => file.name).join(', ')
    selectedFileName.value = fileNames
  } else {
    selectedFileName.value = ''
    fileArray.value = []
  }
}

const back = () => {
  router.push(`/patients/${patient_id}`)
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
const createRecord = async () => {
  console.log()
  try {
    const formData = new FormData()
    formData.append('patient_id', router.currentRoute.value.params.id)
    formData.append('inpatient_status', router.currentRoute.value.params.status)
    formData.append('weight', record.value.weight)
    formData.append('blood_pressure', record.value.blood_pressure)
    formData.append('blood_glucose', record.value.blood_glucose)
    formData.append('heart_rate', record.value.heart_rate)
    formData.append('temperature', record.value.temperature)
    formData.append('repository_rate', record.value.repository_rate)
    formData.append('oxygen_level', record.value.oxygen_level)
    // formData.append('vital_sign', record.value.vital_sign)
    formData.append('prescription', record.value.prescription)
    formData.append('disease', record.value.disease)
    formData.append('clinical_notes', record.value.clinical_notes)
    formData.append('doctor_id', selectedDoctor.value)
    // Array.from(fileArray.value)
    if (Array.isArray(fileArray.value) && fileArray.value.length > 0) {
      console.log('FCUKING HERE>>>>>>>>>>>>>>>>>>>>>>>>>', record.value.files_records)
      Array.from(fileArray.value).forEach((file, index) => {
        formData.append(`files_records[${index}]`, file)
      })
    }

    const response = await fetch('http://127.0.0.1:8000/api/medical-records', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })
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
      break
    case 2:
      showFormOne.value = false
      showFormTwo.value = true
      showFormThree.value = false
      break
    case 3:
      showFormOne.value = false
      showFormTwo.value = false
      showFormThree.value = true
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
  color: #343a40;
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

.btnRegister {
  background-color: #4da8f5;
  color: #fff;
}

.btnRegister:hover {
  background-color: #74b9f3;
  cursor: pointer;
}
.btnRegister:active {
  border: 2px solid #74b9f3;
}
</style>
