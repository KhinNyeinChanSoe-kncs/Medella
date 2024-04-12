<template>
  <div>
    <div class="row mt-lg-4 mt-md-4 mt-sm-2">
      <!-- :class="showAllButton ? 'col-md-3 offset-1 h3' : 'col-md-3 offset-1 h3'" -->
      <div
        :class="
          showAllButton
            ? 'col-lg-5 col-md-5 col-sm-6 col-12 offset-lg-1 offset-md-1 h3 d-flex'
            : 'col-lg-4 col-md-5 col-sm-12  offset-lg-1 offset-md-1 h3 d-flex'
        "
        alt="User List Heading"
      >
        <span :style="{ display: showAllButton ? 'block' : 'none' }">Current&nbsp;</span>Room
        Records
      </div>

      <div
        :class="
          showAllButton
            ? 'col-lg-3 col-md-5 offset-md-0 col-sm-6 col-10 offset-lg-1 mb-3 '
            : 'col-lg-3 col-md-5 offset-md-0 col-sm-8 col-10 offset-lg-2 mb-3'
        "
      >
        <div class="search-container ms-md-5">
          <form @submit.prevent="onFormSubmit">
            <input
              type="text"
              class="form-control form-control-sm"
              id="searchInput"
              v-model="searchKeyword"
              @change="handleKeyDown"
              placeholder="Search by Patient Name"
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
          <!-- <button class="btn px-3 ms-2 actionBtn" alt="add button" @click="createRoom">
            <img src="./../assets/plus.svg" class="me-1" alt="" /> Add
          </button> -->
          <button
            class="btn px-3 ms-2 actionBtn"
            alt="add button"
            @click="handleFilter('current')"
            :style="{ display: showAllButton ? 'none' : 'block' }"
          >
            Current Rooms
          </button>
          <button
            class="btn px-3 ms-2 actionBtn"
            alt="add button"
            @click="handleFilter('all')"
            :style="{ display: showAllButton ? 'block' : 'none' }"
          >
            All Rooms
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
                  <th scope="col">Patient ID</th>

                  <th scope="col">Patient Name</th>
                  <th scope="col">Room Number</th>
                  <!-- <th scope="col">Status</th> -->
                  <th scope="col">Start Date</th>
                  <th scope="col">End Date</th>
                </tr>
              </thead>
              <tbody class="table-group-divider">
                <tr v-for="(record, index) in recordList" :key="index">
                  <td class="id-column">{{ ++index }}</td>
                  <td>{{ record.patient_id }}</td>
                  <td>{{ record.patient_name }}</td>
                  <td>{{ record.room_number }}</td>
                  <!-- <td>{{ record.inpatient ===  }}</td> -->
                  <td>{{ record.start_date }}</td>
                  <td>{{ record.end_date }}</td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-1"></div>
    </div>
    <!-- <ul>
        <li v-for="user in recordList" :key="user.id">{{ user.name }}</li>
      </ul> -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const recordList = ref([])
const storedToken = ref(localStorage.getItem('accessToken'))
const showAllButton = ref(false)
const searchKeyword = ref('')
const status = ref('all')
onMounted(async () => {
  await getPatientRoomRecords()
})
const handleFilter = (param) => {
  status.value = param
  if (param === 'all') {
    showAllButton.value = false
  } else {
    showAllButton.value = true
  }
  getPatientRoomRecords()
}
const handleKeyDown = () => {
  if (searchKeyword.value === '') {
    onFormSubmit()
  }
}
//get all user start
const getPatientRoomRecords = async () => {
  const formData = new FormData()
  formData.append('status', status.value)
  try {
    const response = await fetch('http://127.0.0.1:8000/api/filter/patient-room-records', {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${storedToken.value}`
      },
      body: formData
    })
    const data = await response.json()
    if (response.ok) {
      recordList.value = data.data
      // showAllButton.value = false
      console.log('response data', data.data)
    } else {
      console.log('Error:', data.message)
    }
  } catch (e) {
    console.log(e)
  }
}
//get all user end

//search functions start
const getSearchResults = async () => {
  //   console.log('>>>>>>>>>>>>>>>>>>>>>>here')
  const formData = new FormData()
  formData.append('keyword', searchKeyword.value)
  formData.append('status', status.value)

  try {
    const response = await fetch('http://127.0.0.1:8000/api/search/patient-room-records', {
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
      recordList.value = data.data
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
    getPatientRoomRecords()
  } else {
    getSearchResults()
  }
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
  width: 280px;
  color: #343a40;
}
#searchInput::placeholder {
  color: #b0bec5;
}
@media only screen and (max-width: 575.98px) {
  /* CSS rules for extra small screens */
  #searchInput {
    width: 250px;
    color: #343a40;
  }
  #searchInput::placeholder {
    font-size: 14px;
  }
}
@media only screen and (min-width: 768px) and (max-width: 991.98px) {
  #searchInput {
    width: 230px;
    color: #343a40;
  }
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
</style>
