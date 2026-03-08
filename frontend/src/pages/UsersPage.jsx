import { useEffect, useState } from 'react';
import api from '../api/client';
import DataTable from '../components/DataTable';

export default function UsersPage() {
  const [rows, setRows] = useState([]);
  useEffect(() => { api.get('/users').then(r => setRows(r.data)); }, []);
  return <DataTable columns={[{key:'User_Id',label:'ID'},{key:'Username',label:'Username'},{key:'Email',label:'Email'},{key:'Phone',label:'Phone'},{key:'Role_Id',label:'Role'}]} rows={rows} />;
}
