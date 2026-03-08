import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import api from '../api/client';
import DataTable from '../components/DataTable';

export default function InventoryPage() {
  const [rows, setRows] = useState([]);
  useEffect(() => { api.get('/cars').then(r => setRows(r.data)); }, []);
  const columns = [{key:'Item_Id',label:'ID'},{key:'Brand_Name',label:'Brand'},{key:'Model_Name',label:'Model'},{key:'Registration_No',label:'Reg No'},{key:'Make_Year',label:'Make Year'},{key:'Price',label:'Price'},{key:'Status_Code',label:'Status'}];
  return <div className="space-y-3"><div className="flex justify-between"><h2 className="text-xl font-bold">Inventory</h2><Link to="/inventory/new" className="bg-blue-600 text-white px-3 py-2 rounded">Add Car</Link></div><DataTable columns={columns} rows={rows} /></div>;
}
