<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <!-- :class="showAllButton ? 'col-md-3 offset-1 h3' : 'col-md-3 offset-1 h3'" -->
      <div
        class="col-lg-3 col-md-3 col-sm-3 offset-lg-1 offset-md-1 offset-sm-0 h3 col-md-3 offset-0 h3 mt-2 mb-2"
        alt="User List Heading"
      >
        <span :style="{ display: showAllButton ? 'block' : 'none' }">Available&nbsp;</span>Room List
      </div>

      <div class="col-lg-4 container-lg col-md-8 col-sm-6 col-12 offset-lg-3 mb-3">
        <div class="search-container ms-md-5">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by Room Number and Type"
            />
            <button
              class="btn btn-sm px-2 actionBtn ms-1"
              type="submit"
              id="searchButton"
              alt="search button"
            >
              <img src="./../assets/search.svg" alt="" />
            </button>
          </form>

          <button
            class="btn px-2 ms-2 actionBtn"
            alt="add button"
            @click="getAvailableRooms"
            :style="{ display: showAllButton ? 'none' : 'block' }"
          >
            Available Room
          </button>
          <button
            class="btn btn-primary px-2 ms-2 actionBtn"
            alt="add button"
            @click="getRoomList"
            :style="{ display: showAllButton ? 'block' : 'none' }"
          >
            All Rooms
          </button>
          <button
            class="btn px-3 ms-2 actionBtn"
            alt="add button"
            @click="createRoom"
            v-show="user.role_id === 1"
          >
            <!-- <div class="d-flex"> -->
            <img src="./../assets/plus.svg" class="me-1" alt="" />
            <!-- <span style="color: #fff"> -->
            Add
            <!-- </span> -->
            <!-- </div> -->
          </button>
        </div>
      </div>
      <!-- <div class="col-md-1"></div> -->
    </div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2 mt-2">
      <div class="offset-lg-1 col-lg-11 col-md-12 col-12 col-sm-12">
        <div class="card shadow-sm overflow-x-auto">
          <div class="card-body">
            <table class="table">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Room Number</th>
                  <th scope="col">Room Type</th>
                  <th scope="col">Charges</th>
                  <th scope="col">Action</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="room in roomList" :key="room.id">
                  <td class="id-column">{{ room.id }}</td>
                  <td>{{ room.room_number }}</td>
                  <td>{{ room.room_type }}</td>
                  <td>{{ room.charges }}</td>
                  <td>
                    <button class="btn btnDetail btn-sm me-2" @click="viewRoomDetails(room.id)">
                      <i class="fi fi-rs-eye"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-1"></div>
    </div>
    <!-- <ul>
        <li v-for="user in roomList" :key="user.id">{{ user.name }}</li>
      </ul> -->
  </div>
</template>

<script setup>
import router from '@/router'
import { ref, onMounted } from 'vue'

const roomList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const showAllButton = ref(false)
const searchKeyword = ref('')
const user = ref({})
onMounted(async () => {
  await getRoomList()
  await getUser()
})
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
//get all user start
const getRoomList = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/rooms', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    const data = await response.json()
    if (response.ok) {
      roomList.value = data.data
      showAllButton.value = false
      console.log('response data', data.data)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user end
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
    } else {
      console.log('error', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//search functions start
const getSearchedroomList = async () => {
  //   console.log('>>>>>>>>>>>>>>>>>>>>>>here')
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)

  try {
    const response = await fetch('http://127.0.0.1:8000/api/search-clinic', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })

    if (response.ok) {
      const data = await response.json()
      console.log('HEREEEEEEEEEEE WE GO AGAINNNNNNNNNNNNNNNNNNNN', data)
      roomList.value = data.data
      console.log('search result', data)
    } else {
      console.log('Error:', response)
    }
  } catch (e) {
    console.log(e)
  }
}
const onFormSubmit = () => {
  if (searchKeyword.value.trim() === '') {
    getRoomList()
  } else {
    getSearchedroomList()
  }
}
const getAvailableRooms = async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/available-rooms', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      }
    })
    if (response.ok) {
      const data = await response.json()
      roomList.value = data.data
      showAllButton.value = true
    } else {
      console.log('Error:', response.message)
    }
  } catch (e) {
    console.log(e)
  }
}
const createRoom = () => {
  router.push('/create-room')
}
const viewRoomDetails = (param) => {
  router.push({
    path: `/rooms/${param}`
  })
}
</script>

<style scoped>
* {
  color: #343a40;
}
.search-container,
.search-container form {
  /* width: 350px; */
  display: flex;
}
#searchInput {
  width: 260px;
  color: #343a40;
}
#searchInput::placeholder {
  color: #b0bec5;
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
  width: 50px !important;
}
/* Extra Small devices (portrait phones) */
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  #searchInput {
    width: 220px;
    color: #343a40;
  }
  #searchInput::placeholder {
    font-size: 14px;
  }
}
</style>
