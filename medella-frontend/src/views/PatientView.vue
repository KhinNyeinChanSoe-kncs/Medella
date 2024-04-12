<template>
  <div>
    <div class="container">
      <div v-if="showAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Patient Discharged Successfully.</strong>
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
    </div>
    <!-- Modal -->
    <div
      class="modal fade"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="dischargeModal"
      aria-hidden="true"
      id="modal"
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
          <div class="modal-body fw-bold">
            <div class="justify-content-center text-center">
              <img src="./../assets/veterinarian.png" class="modal-image" alt="" />
            </div>
            You are about to discharge a patient. <br />
            Do you want to proceed the discharge process?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              No,Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              @click="dischargePatient()"
              data-bs-dismiss="modal"
            >
              Yes, Discharge
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Discharge Patient Modal End -->

    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="col-lg-3 col-md-5 col-sm-12 offset-lg-1 offset-md-1 h3" alt="Doctor List Heading">
        Patient List
      </div>

      <div class="col-lg-3 col-md-6 offset-md-0 col-sm-6 col-10 offset-lg-3 mb-3">
        <div class="search-container">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by name, email, and phone"
            />
            <button
              class="btn btn-md px-2 actionBtn ms-1"
              type="submit"
              id="searchButton"
              alt="search button"
            >
              <img src="./../assets/search.svg" alt="" />
            </button>
          </form>
          <button
            class="btn btn-primary px-3 ms-2 actionBtn"
            alt="add button"
            @click="createPatient"
            v-show="user.role_id !== 4"
          >
            <div class="d-flex">
              <img src="./../assets/plus.svg" class="me-1" alt="" />
              <span class="ms-2" style="color: #fff">Add</span>
            </div>
          </button>
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Name</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Parent Name</th>
                  <th scope="col">InPatient</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(patient, index) in patientList.data" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ patient.user_name }}</td>
                  <td>{{ patient.user_phone }}</td>
                  <td>{{ patient.patient_parent_name }}</td>
                  <td>
                    <div class="form-check form-switch">
                      <input
                        class="form-check-input inPatientStatus"
                        type="checkbox"
                        role="switch"
                        id="flexSwitchCheckDefault"
                        :checked="patient.inpatient"
                        :disabled="!patient.inpatient"
                        data-bs-toggle="modal"
                        data-bs-target="#modal"
                        @change="handleDischargeID(patient.patient_id)"
                      />
                    </div>
                  </td>
                  <td>
                    <button class="btn btnDetail btn-sm" @click="getDetails(patient.patient_id)">
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <nav aria-label="Page navigation example">
                  <ul class="pagination">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                      <a class="page-link" href="#" aria-label="Previous" @click="prevPage">
                        <span aria-hidden="true">&laquo;</span>
                      </a>
                    </li>
                    <li
                      class="page-item"
                      v-for="page in lastPage"
                      :key="page"
                      :class="{ active: currentPage === page }"
                    >
                      <a class="page-link" href="#" @click="changePage(page)">{{ page }}</a>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === lastPage }">
                      <a class="page-link" href="#" aria-label="Next" @click="nextPage">
                        <span aria-hidden="true">&raquo;</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <div class="col-1"></div>
    </div>
    <!-- <ul>
      <li v-for="user in doctorList" :key="user.id">{{ user.name }}</li>
    </ul> -->
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const patientList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const currentPage = ref(1)
const lastPage = ref()
const paginationLink = ref([])
const searchKeyword = ref('')
const discharge_patient_id = ref('')
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
const user = ref({})
onMounted(async () => {
  await getPatientList()
  await getUser()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
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
      console.log('USER INFORMATION: ', user.value)
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const dischargePatient = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/discharge/patients/${discharge_patient_id.value}`,
      {
        method: 'PUT',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
      }
    )
    const data = await response.json()
    if (response.ok) {
      showAlert.value = true
    } else {
      showError.value = true
      error_msg.value = data.message
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user start
const getPatientList = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/patients', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      patientList.value = data.data

      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
    } else {
      const data = await response.json()
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const createPatient = () => {
  router.push('/create-patient')
}
const getSearchedPatientList = async () => {
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)

  try {
    const response = await fetch('http://127.0.0.1:8000/api/search-patients', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })
    // console.log('Response:', await response.json())
    if (response.ok) {
      const data = await response.json()
      patientList.value = data.data
      currentPage.value = data.data.current_page
      lastPage.value = data.data.last_page
      paginationLink.value = data.data.links
      console.log('Searched Result', data.data)
    } else {
      console.log('Error:', response.statusText)
    }
  } catch (e) {
    console.log(e)
  }
}

const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getPatientList()
  } else {
    getSearchedPatientList()
  }
}
// search functions end

//details function start
const getDetails = (param) => {
  console.log('Parameter:', param)
  router.push({
    path: `/patients/${param}`
  })
}
const handleDischargeID = (param) => {
  discharge_patient_id.value = param
  console.log('discharge patient>>>>>>>>>>', discharge_patient_id.value)
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
}
//details function end

const changePage = async (page) => {
  currentPage.value = page
  await getPatientList()
}
const nextPage = async () => {
  if (currentPage.value < lastPage.value) {
    currentPage.value++
    await getPatientList()
  }
}
const prevPage = async () => {
  if (currentPage.value > 1) {
    currentPage.value--
    await getPatientList()
  }
}
</script>

<style scoped>
* {
  color: #343a40;
}
.inPatientStatus {
  cursor: pointer;
}
.inPatientStatus:checked {
  background-color: #4da8f5 !important;
}
.search-container,
.search-container form {
  /* width: 350px; */
  display: flex;
}
#searchInput {
  width: 280px;
  color: #343a40;
}

.text-button {
  display: flex;
  align-items: center;
  justify-content: center;
}

.actionBtn {
  background-color: #74b9f3;
  border: 0;
  color: #fff;
}
.actionBtn:hover {
  background-color: #4da8f5;
  color: #fff;
  cursor: pointer;
}
.btnDetail {
  border: 2px solid #c9e3f9;
}

.btnDetail:hover {
  background-color: #74b9f3;

  cursor: pointer;
}
.btnDetail:active {
  border: 2px solid #74b9f3;
}
.page-item {
  color: #343a40;
}
.page-link,
.page-link span {
  text-decoration: none;
  color: #343a40;
}
.page-link:hover,
.page-link span:hover {
  color: #74b9f3;
  cursor: pointer;
}
.id-column {
  width: 50px;
}
#searchInput::placeholder {
  color: #b0bec5;
}
.modal-image {
  width: 300px;
  height: auto;
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
