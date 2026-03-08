import { Navigate } from 'react-router-dom';
import DashboardLayout from '../layouts/DashboardLayout';
import LoginPage from '../pages/LoginPage';
import DashboardPage from '../pages/DashboardPage';
import InventoryPage from '../pages/InventoryPage';
import CrudPage from '../pages/CrudPage';
import UsersPage from '../pages/UsersPage';
import CarFormPage from '../pages/CarFormPage';
import MastersPage from '../pages/MastersPage';

const Private = ({ children }) => (localStorage.getItem('access_token') ? children : <Navigate to="/login" />);

export const appRoutes = [
  { path: '/login', element: <LoginPage /> },
  {
    path: '/',
    element: <Private><DashboardLayout /></Private>,
    children: [
      { index: true, element: <DashboardPage /> },
      { path: 'inventory', element: <InventoryPage /> },
      { path: 'inventory/new', element: <CarFormPage /> },
      { path: 'brands', element: <CrudPage resource="brands" title="Brands" /> },
      { path: 'models', element: <CrudPage resource="models" title="Models" /> },
      { path: 'features', element: <CrudPage resource="features" title="Features" /> },
      { path: 'companies', element: <CrudPage resource="companies" title="Companies" /> },
      { path: 'users', element: <UsersPage /> },
      { path: 'masters', element: <MastersPage /> }
    ]
  }
];
