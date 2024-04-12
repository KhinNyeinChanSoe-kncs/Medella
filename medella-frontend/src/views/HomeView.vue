<template>
  <div>
    <!-- Modal -->
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
              Cancel
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
    <div class="row my-2 banner">
      <div class="col-lg-4 col-md-5 col-sm-10">
        <button @click="toggle" class="btn ms-1 btnMenu">
          <i class="fi fi-br-menu-burger humbugerMenu"></i>
        </button>
        <router-link to="/dashboard" v-show="user.role_id !== 4">
          <span class="ms-3 ms-sm-0 hspName">Medella Hospital</span>
        </router-link>
        <router-link to="/medical-records" v-show="user.role_id === 4">
          <span class="ms-3 ms-sm-0 hspName">Medella Hospital</span>
        </router-link>
      </div>
    </div>
    <div class="row">
      <div class="sidebar col-lg-2 col-md-12 col-sm-12" v-show="toggleMenu">
        <div class="profile">
          <router-link to="/profile">
            <img
              v-if="user.avatar && user.role_id !== 4"
              :src="user.avatar"
              alt="user profile"
              class="avatar"
            />
            <img
              v-if="user.avatar && user.role_id === 4"
              :src="'http://127.0.0.1:8000/images/' + user.avatar"
              alt="user profile"
              class="avatar"
            />
            <span class="ms-3 username">{{ user.name }}</span>
          </router-link>
        </div>

        <ul class="">
          <li
            class="ms-2"
            :class="{ activeMenuItem: activeDashboard, active: activeDashboard }"
            v-show="user.role_id !== 4"
          >
            <router-link to="/dashboard" @click="handleMenu('dashboard')">
              <i class="fi fi-rr-stats"></i>&nbsp;Dashboard
            </router-link>
          </li>

          <li class="ms-2" :class="{ activeMenuItem: activeUsers }" v-show="user.role_id === 1">
            <router-link to="/users" @click="handleMenu('users')"
              ><i class="fi fi-rs-users"></i>&nbsp;Users</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activeDoctors }" v-show="user.role_id !== 4">
            <router-link to="/doctors" @click="handleMenu('doctors')"
              ><i class="fi fi-rr-user-md"></i>&nbsp;Doctors</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activeStaffs }" v-show="user.role_id === 1">
            <router-link to="/staffs" @click="handleMenu('staffs')"
              ><i class="fi fi-rr-user-nurse"></i>&nbsp;Staffs</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activePatients }" v-show="user.role_id !== 4">
            <router-link to="/patients" @click="handleMenu('patients')"
              ><i class="fi fi-rr-hospital-user"></i>&nbsp;Patients</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activeClinics }" v-show="user.role_id !== 4">
            <router-link to="/clinics" @click="handleMenu('clinics')"
              ><i class="fi fi-rs-pharmacy"></i>&nbsp;Clinics</router-link
            >
          </li>

          <li
            class="ms-2"
            :class="{ activeMenuItem: activeDepartments }"
            v-show="user.role_id === 1"
          >
            <router-link to="/departments" @click="handleMenu('departments')"
              ><i class="fi fi-rs-phone-office"></i>&nbsp;Departments</router-link
            >
          </li>

          <li class="ms-2" :class="{ activeMenuItem: activeMedicalRecords }">
            <router-link to="/medical-records" @click="handleMenu('medical_records')"
              ><i class="fi fi-rr-folder-open"></i>&nbsp;Medical Records</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activeBD }" v-show="user.role_id === 1">
            <router-link to="/birth-and-death" @click="handleMenu('born_death')">
              <i class="fi fi-rr-baby"></i>&nbsp;Born-Death</router-link
            >
          </li>
          <li class="ms-2" :class="{ activeMenuItem: activeRooms }" v-show="user.role_id !== 4">
            <router-link to="/rooms" @click="handleMenu('rooms')"
              ><i class="fi fi-rr-procedures"></i>&nbsp;Rooms</router-link
            >
          </li>
          <li
            class="ms-2"
            :class="{ activeMenuItem: activePatientRooms }"
            v-show="user.role_id !== 4"
          >
            <router-link to="/patient-rooms" @click="handleMenu('patient_rooms')"
              ><i class="fi fi-rs-bed-bunk"></i>&nbsp;Patient-Rooms</router-link
            >
          </li>
          <li>
            <button
              class="btn btnLogout text-decoration-none"
              data-bs-toggle="modal"
              data-bs-target="#logoutConformationModal"
            >
              <i class="fi fi-rs-sign-out-alt"></i>&nbsp;Logout
            </button>
          </li>
        </ul>
      </div>
      <div class="col">
        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const toggleMenu = ref(false)
let user = reactive({})
const router = useRouter()
const storedToken = computed(() => localStorage.getItem('accessToken'))
const activeDashboard = ref(true)
const activeUsers = ref(false)
const activeDoctors = ref(false)
const activeStaffs = ref(false)
const activePatients = ref(false)
const activeClinics = ref(false)
const activeDepartments = ref(false)
const activeMedicalRecords = ref(false)
const activeBD = ref(false)
const activeRooms = ref(false)
const activePatientRooms = ref(false)

const handleMenu = (name) => {
  activeDashboard.value = false
  activeUsers.value = false
  activeDoctors.value = false
  activeStaffs.value = false
  activePatients.value = false
  activeClinics.value = false
  activeDepartments.value = false
  activeMedicalRecords.value = false
  activeBD.value = false
  activeRooms.value = false
  activePatientRooms.value = false
  switch (name) {
    case 'dashboard':
      activeDashboard.value = true
      break
    case 'users':
      activeUsers.value = true
      break
    case 'doctors':
      activeDoctors.value = true
      break
    case 'staffs':
      activeStaffs.value = true
      break
    case 'patients':
      activePatients.value = true
      break
    case 'clinics':
      activeClinics.value = true
      break
    case 'departments':
      activeDepartments.value = true
      break
    case 'medical_records':
      activeMedicalRecords.value = true
      break
    case 'born_death':
      activeBD.value = true
      break
    case 'rooms':
      activeRooms.value = true

      break
    case 'patient_rooms':
      activePatientRooms.value = true
      break

    default:
      break
  }
}
// const global = inject('global')
onMounted(async () => {
  console.log('current url name:', router.currentRoute.value.name)
  await checkLoginStatus()
  handleRoute(router.currentRoute.value.name)
})
const handleRoute = (param) => {
  activeDashboard.value = false
  activeUsers.value = false
  activeDoctors.value = false
  activeStaffs.value = false
  activePatients.value = false
  activeClinics.value = false
  activeDepartments.value = false
  activeMedicalRecords.value = false
  activeBD.value = false
  activeRooms.value = false
  activePatientRooms.value = false
  if (param === 'dashboard') {
    activeDashboard.value = true
  } else if (
    param === 'users' ||
    param === 'user-create' ||
    param === 'user-details' ||
    param === 'user-update'
  ) {
    activeUsers.value = true
  } else if (param === 'doctors' || param === 'doctor-patient' || param === 'doctors-details') {
    activeDoctors.value = true
  } else if (param === 'staffs') {
    activeStaffs.value = true
  } else if (
    param === 'patients' ||
    param === 'patients-update' ||
    param === 'patients-details' ||
    param === 'patient-create' ||
    param === 'create-medical-records' ||
    param === 'patient-emr'
  ) {
    activePatients.value = true
  } else if (
    param === 'clinics' ||
    param === 'clinic-staff' ||
    param === 'update-clinic' ||
    param === 'clinic-create'
  ) {
    activeClinics.value = true
  } else if (
    param === 'departments' ||
    param === 'update-department' ||
    param === 'department-create'
  ) {
    activeDepartments.value = true
  } else if (param === 'medical-records' || param === 'medical-records-details') {
    activeMedicalRecords.value = true
  } else if (param === 'birth-and-death') {
    activeBD.value = true
  } else if (param === 'patient-rooms') {
    activePatientRooms.value = true
  } else if (
    param === 'rooms' ||
    param === 'room-create' ||
    param === 'room-details' ||
    param === 'room-update'
  ) {
    activeRooms.value = true
  }
}
const logout = () => {
  localStorage.removeItem('accessToken')
  router.push('/login')
}
const checkLoginStatus = async () => {
  if (!storedToken.value) {
    redirectToLogin()
    return
  }

  try {
    const response = await fetch('http://127.0.0.1:8000/api/me', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    console.log(data.data)
    if (response.ok) {
      user.name = data.data.name
      user.avatar = data.data.avatar
      user.role_id = data.data.role_id
      console.log('Avatar', user.avatar)
      if (user.role_id === 4) {
        handleRoute(router.currentRoute.value.name)
        router.push('/medical-records')
      }
    } else {
      redirectToLogin()
    }
  } catch (e) {
    console.log(e)
    redirectToLogin()
  }
}

const redirectToLogin = () => {
  router.push('/login')
}

const toggle = () => {
  toggleMenu.value = !toggleMenu.value
}
</script>

<style scoped>
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-straight/css/uicons-regular-straight.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css');
@import url('https://cdn-uicons.flaticon.com/2.1.0/uicons-bold-rounded/css/uicons-bold-rounded.css');

.hspName {
  font-size: 24px;
}
.banner {
  border-bottom: 1px solid #ddd;
}
.avatar {
  width: 35px;
  height: 35px;
  border-radius: 50%;
}
.dashboard {
  width: 20px;
  height: 20px;
}
.sidebar {
  position: relative;
  top: 0;
  left: 0;
  border-right: 1px solid #ddd;
}

.sidebar ul {
  list-style-type: none;
  padding: 0;
  margin: 0;
}

.sidebar li,
.profile {
  padding-top: 8px;
  padding-bottom: 8px;
  padding-left: 3px;
  border-bottom: 1px solid #ddd;
}
/* .sidebar li {
  margin-top: 5px;
  margin-bottom: 5px;
} */

.sidebar li:last-child {
  border-bottom: none;
}

.sidebar a,
/* .btnLogout, */
.banner a {
  text-decoration: none;
  color: #343a40;
}

.sidebar a:hover,
.banner a:hover,
.humbugerMenu:hover,
.btnLogout:hover,
.hspName:hover,
.username:hover {
  color: #74b9f3;
  text-shadow: 1px 1px 2px #eaeaea;
}
.btnLogout {
  word-spacing: 2px;
  letter-spacing: 1px;
}
#logout {
  background-color: #f08080;
  color: #343a40;
}
#logout:hover {
  background-color: #f65454;
  color: #fff;
}
.btnMenu:active {
  border: 2px solid #fff !important;
}
.activeMenuItem {
  border-radius: 1px;
  box-shadow: 3px 0px 1.5px rgba(77, 167, 245, 0.7);
}
.active {
  color: #74b9f3 !important;
}
</style>
