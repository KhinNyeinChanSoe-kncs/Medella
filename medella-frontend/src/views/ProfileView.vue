<template>
  <div class="container-lg">
    <!-- Logout Modal -->
    <div
      class="modal fade"
      id="logoutConformationModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="logoutConformationModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="logoutConformationModalLabel">
              Are you sure want to logout?
            </h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">Press Logout Button to continue...</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">
              Close
            </button>
            <button
              type="button"
              class="btn px-4"
              id="logout"
              @click="logout"
              data-bs-dismiss="modal"
            >
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- ChangePasswordModal -->
    <div
      class="modal fade"
      id="changePasswordModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      aria-hidden="true"
      tabindex="-1"
      aria-labelledby="changePasswordModalLabel"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <span class="modal-title h3 text-center" id="changePasswordModalLabel">
              Change Your Password
            </span>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <div
              v-if="showAlert"
              class="alert alert-success alert-dismissible fade show"
              role="alert"
            >
              <strong>Password Changed Successfully.</strong>
              <button
                type="button"
                class="btn-close"
                @click="hideAlert"
                aria-label="Close"
              ></button>
            </div>
            <div
              v-if="showError"
              class="alert alert-error alert-dismissible fade show error"
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
                  type="password"
                  class="form-control"
                  id="oldPassword"
                  placeholder="oldPassword"
                  required
                  v-model="oldPwd"
                  aria-describedby="oldPasswordHelp"
                />
                <label for="oldPassword" class="form-label">Enter Your Old Password*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="password"
                  class="form-control"
                  id="newPassword"
                  placeholder="newPassword"
                  required
                  v-model="newPwd"
                  aria-describedby="newPasswordHelp"
                />
                <label for="newPassword" class="form-label">Enter New Password*</label>
              </div>
              <div class="mb-3 form-floating">
                <input
                  type="password"
                  class="form-control"
                  id="reEnterNewPassword"
                  placeholder="reenterNewPassword"
                  required
                  v-model="retypeNewPwd"
                  aria-describedby="reEnterNewPasswordHelp"
                />
                <label for="reEnterNewPassword" class="form-label">Retype New Password*</label>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button class="btn actionBtn" type="button" @click="(event) => changePassword(event)">
              Change Password
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="banner">
      <div class="d-flex">
        <img
          v-if="user?.avatar && user?.role_id === 4"
          :src="'http://127.0.0.1:8000/images/' + user?.avatar"
          alt="user profile"
          class="avatar"
        />
        <img
          v-if="user?.avatar && user?.role_id !== 4"
          :src="user?.avatar"
          alt="user profile"
          class="avatar"
        />
        <div class="d-inline-block">
          <h4 class="name ms-md-5">{{ user?.name }}</h4>
          <div class="email ms-md-5">
            <img src="./../assets/at.svg" alt="" /><span class="ms-2">{{ user?.email }}</span>
          </div>
          <div class="address ms-md-5">
            <img src="./../assets/house-chimney.svg" alt="" /><span class="ms-2">{{
              user?.address
            }}</span>
          </div>
        </div>
      </div>
      <div class="dropdown">
        <button class="btn border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fi fi-rr-bars-staggered menu"></i>
        </button>
        <ul class="dropdown-menu">
          <li>
            <a
              class="dropdown-item"
              href="#"
              data-bs-toggle="modal"
              data-bs-target="#changePasswordModal"
              ><i class="fi fi-rr-lock"></i>&nbsp;Change Password</a
            >
          </li>
          <li>
            <a
              class="dropdown-item"
              href="#"
              data-bs-toggle="modal"
              data-bs-target="#logoutConformationModal"
            >
              <i class="fi fi-rs-sign-out-alt"></i>&nbsp;Logout</a
            >
          </li>
        </ul>
      </div>
    </div>

    <!-- for patient only role -->
    <div class="row mt-2" v-if="user?.role_id === 4">
      <div class="col-md-4 col-sm-12 mt-3">
        <div class="card shadow-sm">
          <div class="card-header h4 mt-4">Personal Information</div>
          <div class="card-body">
            <table class="border-none table">
              <tr class="tr-height">
                <th class="">User ID :</th>

                <td class="fw-light">{{ user?.id }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Patient ID :</th>

                <td class="fw-light">{{ latestEMR?.patient_id }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Phone :</th>

                <td class="fw-light">{{ user?.phone }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">City :</th>

                <td class="fw-light">{{ user?.city }}</td>
              </tr>

              <tr class="tr-height">
                <th class="">Gender :</th>

                <td class="fw-light">{{ user?.gender }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 mt-3">
        <div class="card shadow-sm">
          <div class="card-header h4">Medical History</div>
          <div class="card-body">
            <table class="table border-none">
              <tr class="tr-height">
                <th class="">Allergy Reactions:</th>
                <td class="fw-light">
                  {{
                    latestEMR.allergies_reactions === 'undefined'
                      ? '---'
                      : latestEMR.allergies_reactions
                  }}
                </td>
              </tr>

              <tr class="tr-height">
                <th class="">Surgery Histories :</th>
                <td class="fw-light">
                  {{
                    latestEMR.surgery_histories === 'undefined'
                      ? '---'
                      : latestEMR?.surgery_histories
                  }}
                </td>
              </tr>
              <tr class="tr-height">
                <th class="">Blood Type :</th>
                <td class="fw-light">{{ latestEMR?.blood_type }}</td>
              </tr>
              <tr class="tr-height">
                <th
                  :class="{ bornHere: latestEMR.born_status === 1 }"
                  class="text-center fw-medium"
                  v-if="latestEMR.born_status !== 'undefined' || latestEMR.born_status === 0"
                  colspan="2"
                >
                  {{ user?.name }} is born here.
                </th>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4 col-sm-12 mt-3">
        <div class="card shadow-sm">
          <div class="card-header h4">Latest Medical Record</div>
          <div class="card-body">
            <table class="border-none table">
              <tr class="tr-height">
                <th class="">Weight :</th>
                <td class="fw-light">{{ latestEMR.weight }}</td>
              </tr>
              <tr v-if="latestEMR.prescription !== 'undefined' && latestEMR.prescription !== null">
                <th class="">Prescription :</th>
                <td class="fw-light">{{ latestEMR.prescription }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Disease :</th>
                <td class="fw-light">{{ latestEMR.disease }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Doctor :</th>
                <td class="fw-light">{{ latestEMR.doctor_name }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Type :</th>
                <td class="fw-light">
                  {{ latestEMR.inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}
                </td>
              </tr>
            </table>
          </div>
          <div class="card-footer">
            <button class="btn actionBtn btn-sm float-end" @click="viewAllRecord">View More</button>
          </div>
        </div>
      </div>
    </div>
    <!-- for admin only role -->
    <div class="d-block mt-sm-3 container mt-md-3" v-if="user?.role_id === 1">
      <div class="admin-card mx-auto">
        <div class="h4 mb-md-4 mt-4">Personal Information</div>
        <div class="">
          <table class="border-none table">
            <tr class="tr-height">
              <th class="">User ID :</th>

              <td class="fw-light">{{ user?.id }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Phone :</th>

              <td class="fw-light">{{ user?.phone }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">City :</th>

              <td class="fw-light">{{ user?.city }}</td>
            </tr>

            <tr class="tr-height">
              <th class="">Gender :</th>

              <td class="fw-light">{{ user?.gender }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Department ID :</th>

              <td class="fw-light">{{ user?.department.department_id }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Department :</th>

              <td class="fw-light">{{ user?.department.department_name }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <!-- for doctor role -->
    <div class="row" v-if="user?.role_id === 3">
      <div class="col-lg-5 col-md-12 col-sm-12 mt-md-5 mt-sm-5 offset-lg-1 offset-md-0">
        <div class="card shadow-sm">
          <div class="card-header h4 mt-md-2">Personal Information</div>
          <div class="card-body">
            <table class="border-none table">
              <tr class="tr-height">
                <th class="">User ID :</th>

                <td class="fw-light">{{ user?.id }}</td>
              </tr>

              <tr class="tr-height">
                <th class="">Doctor ID :</th>

                <td class="fw-light">{{ user?.doctor.doctor_id }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Clinic :</th>

                <td class="fw-light">{{ user?.doctor.clinic_name }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Department :</th>

                <td class="fw-light">{{ user?.doctor.department_name }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">Phone :</th>

                <td class="fw-light">{{ user?.phone }}</td>
              </tr>
              <tr class="tr-height">
                <th class="">City :</th>

                <td class="fw-light">{{ user?.city }}</td>
              </tr>

              <tr class="tr-height">
                <th class="">Gender :</th>

                <td class="fw-light">{{ user?.gender }}</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-10 col-sm-10 mt-md-5 mb-md-5 mt-3">
        <div class="card">
          <div class="card-header h4">Patient List</div>
          <div class="card-body">
            <table class="table">
              <tr class="tr-height">
                <th class="fw-bold">#</th>
                <th class="fw-bold">Name</th>
                <th class="fw-bold">Type</th>
              </tr>
              <tr
                v-for="(patient, index) in patientList.slice(0, 3)"
                :key="index"
                class="tr-height"
              >
                <td>{{ ++index }}</td>
                <td>{{ patient.user_name }}</td>
                <td>
                  {{ patient.inpatient_status === 1 ? 'Inpatient' : 'Outpatient' }}
                </td>
              </tr>
            </table>
          </div>
          <div class="card-footer">
            <button
              class="btn actionBtn btn-sm float-end"
              @click="redirectToDoctorsPatient(user.doctor.doctor_id, user.doctor.doctor_name)"
            >
              View More
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- for medical staff only -->
    <div class="container msContainer" v-if="user?.role_id === 2">
      <div class="mt-md-5 mx-auto admin-card">
        <!-- <div class=""> -->
        <div class="h4 mt-md-5 mt-4">Personal Information</div>
        <div class="">
          <table class="border-none table">
            <tr class="tr-height">
              <th class="">User ID :</th>

              <td class="fw-light">{{ user?.id }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Medical Staff ID :</th>

              <td class="fw-light">{{ user?.medical_staff.medical_staff_id }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Clinic :</th>

              <td class="fw-light">{{ user?.medical_staff.clinic_name }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Department :</th>

              <td class="fw-light">{{ user?.medical_staff.department_name }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">Phone :</th>

              <td class="fw-light">{{ user?.phone }}</td>
            </tr>
            <tr class="tr-height">
              <th class="">City :</th>

              <td class="fw-light">{{ user?.city }}</td>
            </tr>

            <tr class="tr-height">
              <th class="">Gender :</th>

              <td class="fw-light">{{ user?.gender }}</td>
            </tr>
          </table>
        </div>
        <!-- </div> -->
      </div>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'

const user = ref({})
const latestEMR = ref({})
const oldPwd = ref('')
const newPwd = ref('')
const retypeNewPwd = ref('')
const showAlert = ref(false)
const showError = ref(false)
const error_msg = ref([])
const patientList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
onMounted(async () => {
  await getUser()
  await getLatestEMR()
  await getPatientofDoctor()
})

const viewAllRecord = () => {
  router.push(`/patients/${latestEMR.value.patient_id}/emr`)
}
const redirectToDoctorsPatient = (param, name) => {
  router.push({
    path: `/doctors/${param}/patients`,
    params: { param: param, name: name },
    query: { name: name }
  })
}
const getLatestEMR = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/latest-emr', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      latestEMR.value = data.data
      // console.log('data', data.data)
    } else {
      console.log(data.message)
    }
  } catch (e) {
    console.error(e.message)
  }
}
const getPatientofDoctor = async () => {
  try {
    //retrieve doctor_id from param
    const id = user.value.doctor.doctor_id
    const response = await fetch(`http://127.0.0.1:8000/api/doctors/${id}/patients`, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })

    if (response.ok) {
      const data = await response.json()
      console.log('patient list > > >', data.data)
      patientList.value = data.data
      //   console.log('response data', data.data)
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const changePassword = async (event) => {
  if (event) {
    event.preventDefault()
  }

  console.log('here change password')
  try {
    if (newPwd.value !== retypeNewPwd.value) {
      console.log("Hi It's me, fucking error")
      showError.value = true
      error_msg.value =
        'Please enter the same password in both new password form field and retype new password form field.'
      return
    }
    const formData = new FormData()
    formData.append('old', oldPwd.value)
    formData.append('new', newPwd.value)
    const response = await fetch('http://127.0.0.1:8000/api/change_password', {
      method: 'POST',
      body: formData,
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      showAlert.value = true
      oldPwd.value = ''
      newPwd.value = ''
      retypeNewPwd.value = ''
    } else {
      showError.value = true
      error_msg.value = data.message
    }
  } catch (e) {
    console.log(e.message)
  }
}
const hideAlert = () => {
  showAlert.value = false
}

const hideError = () => {
  showError.value = false
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

const logout = () => {
  localStorage.removeItem('accessToken')
  router.push('/login')
}
</script>
<style scoped>
.banner {
  width: 100%;
  height: 250px;
  background-color: #4da8f54d;
  background-image: linear-gradient(to bottom right, #4da8f54d, #4da8f57f, #c9e3f9);
  animation: changeColors 10s ease infinite;
  border-radius: 5px;
  position: relative;
}
@keyframes changeColors {
  0%,
  25% {
    background-image: linear-gradient(to bottom right, #4da8f54d, #4da8f57f, #c9e3f97f);
  }
  25.001%,
  50% {
    background-image: linear-gradient(to bottom right, #c9e3f97f, #4da8f54d, #4da8f5);
  }
  50.001%,
  75% {
    background-image: linear-gradient(to bottom right, #4da8f57f, #c9e3f97f, #4da8f54d);
  }
  75.001%,
  100% {
    background-image: linear-gradient(to bottom right, #4da8f54d, #4da8f57f, #c9e3f97f);
  }
}
.tr-height {
  height: 40px;
}

.card-header,
.card-footer {
  background-color: #fff !important;
  margin-top: 5px;
  margin-bottom: 5px;
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

.alert-success {
  background-color: #4da7f535;
  color: #343a40;
}
.alert-error {
  background-color: #f3909078;
  color: #343a40;
}

.avatar {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  position: absolute;
  top: 55%;
  left: 2%;
  /* border: 1px solid #4da8f5;
  box-sizing: content-box; */
}
.name {
  position: absolute;
  top: 55%;
  left: 11%;
}
.address {
  position: absolute;
  top: 83%;
  left: 11%;
  font-size: 14px;
}
.email {
  position: absolute;
  top: 70%;
  left: 11%;
  font-size: 14px;
}
/* Extra Small devices (portrait phones) */
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */

  .name {
    position: absolute;
    top: 52%;
    left: 33%;
  }
  .address {
    position: absolute;
    top: 75%;
    left: 33%;
    font-size: 14px;
  }
  .email {
    position: absolute;
    top: 65%;
    left: 33%;
    font-size: 14px;
  }
  .tr-height th {
    width: 150px;
  }
}

/* Small devices (landscape phones, 576px and up) */
@media only screen and (min-width: 576px) and (max-width: 767.98px) {
  /* CSS rules for small screens */
  .name {
    position: absolute;
    top: 55%;
    left: 33%;
  }
  .address {
    position: absolute;
    top: 83%;
    left: 33%;
    font-size: 14px;
  }
  .email {
    position: absolute;
    top: 70%;
    left: 33%;
    font-size: 14px;
  }
  .admin-container {
    margin-top: 100px;
  }
  .msContainer {
    margin-top: 80px;
  }
}

/* Medium devices (tablets, 768px and up) */
@media only screen and (min-width: 768px) and (max-width: 991.98px) {
  /* CSS rules for medium screens */
  .name {
    position: absolute;
    top: 55%;
    left: 15%;
  }
  .address {
    position: absolute;
    top: 83%;
    left: 15%;
    font-size: 14px;
  }
  .email {
    position: absolute;
    top: 70%;
    left: 15%;
    font-size: 14px;
  }
  .msContainer {
    margin-top: 80px;
  }
}

/* Large devices (desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  /* CSS rules for large screens */
}

.dropdown {
  position: absolute;
  top: 3%;
  right: 0;
}
.menu {
  font-size: 20px;
}
.menu:hover,
.menu:active,
.menu:focus {
  color: #4da8f5;
}
.dropdown:focus {
  border: none !important;
}
.dropdown:hover {
  border: none;
}
.dropdown-item:hover {
  color: #4da8f5;
}
.admin-card {
  width: 500px;
}
.bornHere {
  color: #4da8f5;
}
</style>
