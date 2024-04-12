<template>
  <div class="container">
    <div
      class="modal fade"
      id="exampleModal"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      aria-labelledby="logoutConformationModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Deactive Patient Account</h1>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
          </div>
          <div class="modal-body">
            <img src="./../assets/trash.png" class="trash" alt="tarsh sticker" />
            <p class="mt-2">
              Deactivating account will remove this patient information from the system. This cannot
              be undone. <br />
              Are you sure want to proceed?
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              No, Cancel
            </button>
            <button
              type="button"
              class="btn btn-primary"
              id="btnDelete"
              data-bs-dismiss="modal"
              @click="deactivatePatient(patient_id)"
            >
              Yes, Deactivate
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row mb-md-5 mb-5">
      <div class="col-lg-1 col-md-1 col-sm-2 ms-md-2 ms-sm-0">
        <button @click="back" class="btn btnBack">
          <i class="fi fi-rs-arrow-left backarrow backarrow"></i>
        </button>
      </div>
      <div class="col-lg-4 col-md-5 col-sm-5 offset-lg-3 offset-md-2 offset-sm-0 mt-md-2 col-8">
        <span id="title" class="fw-medium mx-auto h3">Patient Information</span>
      </div>
      <div class="col-lg-3 col-md-3 col-sm-5 mt-md-2 col-4">
        <button
          class="btn btn-edit ms-lg-4 me-lg-2 m-sm-0"
          @click="patientUpdate(patient.patient_id)"
        >
          <img src="./../assets/user-pen.svg" alt="" />
        </button>
        <button class="btn btn-trash" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <img src="./../assets/trash.svg" alt="" />
        </button>
      </div>
    </div>
    <div class="row mt-md-6">
      <div class="col-7 offset-2">
        <div class="d-flex">
          <!-- <img :src="profile" alt="" /> -->
        </div>
        <table class="table table-borderless">
          <tbody>
            <div class="groupInfoOne rounded mb-5">
              <div class="groupHeadingOne">Personal Information</div>
              <tr class="tableRow">
                <td class="fw-medium">User ID</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_id }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Patient ID</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient.patient_id }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Name</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_name }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Parent Name</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.parent_name }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Email</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_email }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Phone</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_phone }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Age</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ age }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">City</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_city }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Address</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_address }}</td>
              </tr>
              <tr class="tableRow">
                <td class="fw-medium">Gender</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.user_gender }}</td>
              </tr>

              <tr class="tableRow">
                <td class="fw-medium">Date of Birth</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.dob }}</td>
              </tr>

              <tr class="tableRow">
                <td class="fw-medium">Type</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">
                  {{ patient?.inpatient === 0 ? 'Outpatient' : 'Inpatient' }}
                </td>
              </tr>
            </div>
            <!-- <div class="" style="height: 30px; width: 30px"></div> -->
            <!-- Medical Related Information -->
            <div class="groupInfoTwo rounded">
              <div class="groupHeadingTwo">Health Information</div>
              <tr class="tableRow">
                <td class="fw-medium">Blood Type</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.blood_type }}</td>
              </tr>

              <tr
                class="tableRow"
                v-show="
                  patient?.allergies_reactions && patient?.allergies_reactions !== 'undefined'
                "
              >
                <td class="fw-medium">Allergy Reactions</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.allergies_reactions }}</td>
              </tr>
              <tr
                v-show="patient?.surgery_histories && patient?.surgery_histories !== 'undefined'"
                class="tableRow"
              >
                <td class="fw-medium">Surgery History</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="">{{ patient?.surgery_histories }}</td>
              </tr>

              <tr class="tableRow">
                <td class="fw-medium">Emergency Contact</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="" style="color: #f65454">{{ patient?.emergency_contact_number }}</td>
              </tr>
              <tr v-show="patient.born_status && patient.born_status !== 'false'" class="tableRow">
                <td class="fw-medium">Born Status</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="born">Born Here</td>
              </tr>
              <tr v-show="patient.born_date" class="tableRow">
                <td class="fw-medium">Born Date</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="born">{{ patient?.born_date }}</td>
              </tr>
              <tr v-show="patient.dead_status && patient.dead_status !== 'false'" class="tableRow">
                <td class="fw-medium">Death Status</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="dead">Dead Here</td>
              </tr>
              <tr v-show="patient.dead_date" class="tableRow">
                <td class="fw-medium">Death Date</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="dead">{{ patient?.dead_date }}</td>
              </tr>
            </div>
          </tbody>
        </table>
      </div>
    </div>
    <div class="d-flex justify-content-center text-center mb-5 mt-3">
      <button
        class="btn btnRecord me-3"
        @click="addMedicalRecord(patient.patient_id, patient.inpatient)"
      >
        Add Medical Record
      </button>
      <button class="btn btnRecord" @click="getAllEMR(patient.patient_id)">
        View All Medical Record
      </button>
    </div>
  </div>
</template>
<script setup>
import router from '@/router'
import { onMounted, ref } from 'vue'
const storedToken = ref(localStorage.getItem('accessToken'))
const patient_id = ref('')
const patient = ref({})
const age = ref('')

const addMedicalRecord = (patient_id, inpatient_status) => {
  router.push(`/patients/${patient_id}/${inpatient_status}/medical-record/create`)
}
const back = () => {
  router.back()
}

const calculateAge = (dob) => {
  const current_year = new Date().getFullYear()
  const born_year = dob.split('-')[0]
  age.value = current_year - born_year
}
onMounted(async () => {
  getPatinetInfo()
})
const getAllEMR = (patient_id) => {
  router.push(`/patients/${patient_id}/emr`)
}
const getPatinetInfo = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/patients/${router.currentRoute.value.params.id}`,
      {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
      }
    )
    const data = await response.json()
    if (response.ok) {
      patient.value = data.data
      console.log('inpatient status', patient.value.inpatient)
      calculateAge(patient.value.dob)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const deactivatePatient = async () => {
  try {
    const response = await fetch(
      `http://127.0.0.1:8000/api/patients/${patient_id.value}/deactivate`,
      {
        method: 'POST',
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${storedToken.value}`
        }
      }
    )
    // const data = await response.json()
    if (response.ok) {
      console.log('Success')
      router.push('/patients')
    } else {
      console.log('Error', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const patientUpdate = (param) => {
  router.push(`/patients/update/${param}`)
}
</script>

<style scoped>
.born {
  color: #6bd38a;
}
.dead {
  color: #b0bec5;
}
.backarrow {
  font-size: 30px;
  color: #343a40;
}
.backarrow:hover {
  color: #4da8f5;
}
* {
  /* font-size: 32px; */
  color: #343a40;
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

.btn-trash {
  border: 2px solid #f65454;
}

.btn-trash:hover {
  background-color: #f08080;
}

#btnDelete {
  background-color: #f08080;
  color: #343a40;
}
#btnDelete:hover {
  background-color: #f65454;
  color: #fff;
}
.btnBack:active {
  border: 2px solid #fff;
}
.trash {
  display: block;
  margin: auto;
  width: 100px;
  height: 100px;
}
.groupInfoOne {
  border: 1px solid #b0bec5;
  padding-top: 25px;
  padding-bottom: 25px;
  padding-left: 50px;
  position: relative;
  z-index: 0;
}
.groupHeadingOne {
  position: absolute;
  color: #b0bec5;
  font-size: 14px;
  top: -4%;
  left: 2%;
  z-index: 1;
}
.groupInfoTwo {
  border: 1px solid #b0bec5;
  padding-top: 25px;
  padding-bottom: 25px;
  padding-left: 50px;
  position: relative;
  z-index: 0;
}
.groupHeadingTwo {
  position: absolute;
  color: #b0bec5;
  font-size: 14px;
  top: -12%;
  left: 2%;
  z-index: 1;
}
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  .groupHeadingTwo {
    top: -5%;
  }
}

/* Small devices (landscape phones, 576px and up) */
@media only screen and (min-width: 576px) and (max-width: 767.98px) {
  /* CSS rules for small screens */
}

/* Medium devices (tablets, 768px and up) */
@media only screen and (min-width: 768px) and (max-width: 991.98px) {
  /* CSS rules for medium screens */
  .groupHeadingTwo {
    top: -8%;
  }
}

/* Large devices (desktops, 992px and up) */
@media only screen and (min-width: 992px) {
  /* CSS rules for large screens */
  .groupHeadingTwo {
    top: -8%;
  }
}

.btnRecord {
  background-color: #4da8f5;
  color: #fff;
}

.btnRecord:hover {
  background-color: #74b9f3;
  cursor: pointer;
}
.btnRecord:active,
.btnRecord:focus {
  border: 2px solid #74b9f3;
}
.tableRow {
  height: 40px !important;
}
</style>
