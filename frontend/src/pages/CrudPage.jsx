import { useEffect, useState } from 'react';
import { z } from 'zod';
import api from '../api/client';

const schema = z.object({ name: z.string().min(2) });

export default function CrudPage({ resource, title }) {
  const [rows, setRows] = useState([]); const [name, setName] = useState(''); const [message, setMessage] = useState('');
  const load = () => api.get(`/${resource}`).then(r => setRows(r.data));
  useEffect(load, [resource]);

  const add = async () => {
    const parsed = schema.safeParse({ name });
    if (!parsed.success) {
      setMessage(parsed.error.issues[0]?.message || 'Invalid value');
      return;
    }
    await api.post(`/${resource}`, parsed.data);
    setName('');
    setMessage('Created');
    load();
  };

  return <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow"><h2 className="text-xl font-bold mb-3">{title}</h2>{message ? <p className="text-sm mb-2 text-blue-600 dark:text-blue-400">{message}</p> : null}<div className="flex gap-2 mb-3"><input className="border p-2 flex-1 bg-white dark:bg-slate-800" value={name} onChange={e=>setName(e.target.value)} /><button onClick={add} className="bg-blue-600 text-white px-3 rounded">Add</button></div><ul className="space-y-2">{rows.map(r => <li key={Object.values(r)[0]} className="p-2 border border-slate-200 dark:border-slate-700 rounded">{r.Brand_Name || r.Model_Name || r.Feature_Name || r.Company_Name}</li>)}</ul></div>;
}
