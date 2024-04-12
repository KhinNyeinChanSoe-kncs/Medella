import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '@/views/LoginView.vue'
import DashboardView from '@/views/DashboardView.vue'
import UsersView from '@/views/UsersView.vue'
import ErrorView from '@/views/ErrorView.vue'
import DoctorView from '@/views/DoctorView.vue'
import StaffView from '@/views/StaffView.vue'
import PatientView from '@/views/PatientView.vue'
import ClinicView from '@/views/ClinicView.vue'
import DepartmentView from '@/views/DepartmentView.vue'
import MedicalRecordView from '@/views/MedicalRecordView.vue'
import BirthAndDeathView from '@/views/BirthAndDeathView.vue'
import RoomView from '@/views/RoomView.vue'
import PatientRoomsRecordsView from '@/views/PatientRoomsRecordsView.vue'
import ProfileView from '@/views/ProfileView.vue'
import UserDetailsView from '@/views/UserDetailsView.vue'
import UserUpdateView from '@/views/UserUpdateView.vue'
import CreateUserView from '@/views/CreateUserView.vue'
import CreateClinicView from '@/views/CreateClinicView.vue'
import ClinicUpdateView from '@/views/ClinicUpdateView.vue'
import ClinicStaffListView from '@/views/ClinicStaffListView.vue'
import DepartmentUpdateView from '@/views/DepartmentUpdateView.vue'
import CreateDepartmentView from '@/views/CreateDepartmentView.vue'
import DoctorDetailsView from '@/views/DoctorDetailsView.vue'
import RoomDetailsView from '@/views/RoomDetailsView.vue'
import RoomUpdateView from '@/views/RoomUpdateView.vue'
import CreateRoomView from '@/views/CreateRoomView.vue'
import DoctorsPatientListView from '@/views/DoctorsPatientListView.vue'
import CreatePatientView from '@/views/CreatePatientView.vue'
import PatientDetailsView from '@/views/PatientDetailsView.vue'
import PatientUpdateView from '@/views/PatientUpdateView.vue'
import DetailsMedicalRecordView from '@/views/DetailsMedicalRecordView.vue'
import CreateMedicalRecordView from '@/views/CreateMedicalRecordView.vue'
import PatientEMRView from '@/views/PatientEMRView.vue'

const router = createRouter({
    history: createWebHistory(
        import.meta.env.BASE_URL),
    routes: [{
            path: '/',
            name: 'home',
            component: HomeView,
            redirect: '/dashboard',
            children: [{
                    path: '/dashboard',
                    name: 'dashboard',
                    component: DashboardView
                },
                {
                    path: '/users',
                    name: 'users',
                    component: UsersView
                },
                {
                    path: '/create-user',
                    name: 'user-create',
                    component: CreateUserView
                },
                {
                    path: '/users/:id',
                    name: 'user-details',
                    component: UserDetailsView,
                    props: true
                },
                {
                    path: '/users/update/:id',
                    name: 'user-update',
                    component: UserUpdateView,
                    props: true
                },
                {
                    path: '/profile',
                    name: 'profile',
                    component: ProfileView
                },
                {
                    path: '/doctors',
                    name: 'doctors',
                    component: DoctorView
                },
                {
                    path: '/doctors/:id/patients',
                    name: 'doctor-patient',
                    component: DoctorsPatientListView,
                    props: true
                },
                {
                    path: '/doctors/:id',
                    name: 'doctors-details',
                    component: DoctorDetailsView
                },
                {
                    path: '/staffs',
                    name: 'staffs',
                    component: StaffView
                },
                {
                    path: '/patients',
                    name: 'patients',
                    component: PatientView
                },
                {
                    path: '/patients/update/:id',
                    name: 'patients-update',
                    component: PatientUpdateView,
                    props: true
                },
                {
                    path: '/patients/:id',
                    name: 'patients-details',
                    component: PatientDetailsView,
                    props: true
                },
                {
                    path: '/create-patient',
                    name: 'patient-create',
                    component: CreatePatientView
                },
                {
                    path: '/patients/:id/emr',
                    name: 'patient-emr',
                    component: PatientEMRView,
                    props: true
                },
                {
                    path: '/clinics',
                    name: 'clinics',
                    component: ClinicView
                },
                {
                    path: '/clinics/:id/staff',
                    name: 'clinic-staff',
                    component: ClinicStaffListView
                },
                {
                    path: '/clinics/:id/update',
                    name: 'update-clinic',
                    component: ClinicUpdateView,
                    props: true
                },
                {
                    path: '/create-clinic',
                    name: 'clinic-create',
                    component: CreateClinicView
                },
                {
                    path: '/departments',
                    name: 'departments',
                    component: DepartmentView
                },
                {
                    path: '/department/:id/update',
                    name: 'update-department',
                    component: DepartmentUpdateView,
                    props: true
                },
                {
                    path: '/create-department',
                    name: 'department-create',
                    component: CreateDepartmentView
                },
                {
                    path: '/medical-records',
                    name: 'medical-records',
                    component: MedicalRecordView
                },
                {
                    path: 'patients/:id/:status/medical-record/create',
                    name: 'create-medical-records',
                    component: CreateMedicalRecordView,
                    props: true
                },
                {
                    path: '/medical-records/:id',
                    name: 'medical-records-details',
                    component: DetailsMedicalRecordView,
                    props: true
                },
                {
                    path: '/birth-and-death',
                    name: 'birth-and-death',
                    component: BirthAndDeathView
                },
                {
                    path: '/rooms',
                    name: 'rooms',
                    component: RoomView
                },
                {
                    path: '/create-room',
                    name: 'room-create',
                    component: CreateRoomView
                },
                {
                    path: '/rooms/:id',
                    name: 'room-details',
                    component: RoomDetailsView,
                    props: true
                },
                {
                    path: '/rooms/:id/update',
                    name: 'room-update',
                    component: RoomUpdateView,
                    props: true
                },
                {
                    path: '/patient-rooms',
                    name: 'patient-rooms',
                    component: PatientRoomsRecordsView
                },
            ]
        },
        {
            path: '/login',
            name: 'login',
            component: LoginView
        },

        {
            path: "/:pathMatch(.*)*",
            name: "error",
            component: ErrorView,
        },
    ]
})

export default router