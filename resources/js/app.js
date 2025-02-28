import './bootstrap';
import { createApp } from 'vue';

// Importar componentes
import UserManagement from './components/UserManagement.vue';
import StatCard from './components/StatCard.vue';
import Pagination from './components/Pagination.vue';
import CreateUserModal from './components/CreateUserModal.vue';
import EditUserModal from './components/EditUserModal.vue';

// Crear la aplicación Vue
const app = createApp({});

// Registrar componentes
app.component('user-management', UserManagement);
app.component('stat-card', StatCard);
app.component('pagination', Pagination);
app.component('create-user-modal', CreateUserModal);
app.component('edit-user-modal', EditUserModal);

// Montar la aplicación
app.mount('#app');
