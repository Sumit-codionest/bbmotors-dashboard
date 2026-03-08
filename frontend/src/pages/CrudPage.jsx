import { useEffect, useState } from 'react';
import api from '../api/client';

export default function CrudPage({ resource, title }) {
  const [rows, setRows] = useState([]); const [name, setName] = useState('');
  const load = () => api.get(`/${resource}`).then(r => setRows(r.data));
  useEffect(load, [resource]);
  return <div className="bg-white p-4 rounded shadow"><h2 className="text-xl font-bold mb-3">{title}</h2><div className="flex gap-2 mb-3"><input className="border p-2 flex-1" value={name} onChange={e=>setName(e.target.value)} /><button onClick={async()=>{await api.post(`/${resource}`,{name});setName('');load();}} className="bg-blue-600 text-white px-3 rounded">Add</button></div><ul className="space-y-2">{rows.map(r => <li key={Object.values(r)[0]} className="p-2 border rounded">{r.Brand_Name || r.Model_Name || r.Feature_Name || r.Company_Name}</li>)}</ul></div>;
}
