import { useEffect, useState } from 'react';
import api from '../api/client';
import KpiCard from '../components/KpiCard';
import ChartCard from '../components/ChartCard';

export default function DashboardPage() {
  const [summary, setSummary] = useState({});
  const [charts, setCharts] = useState({ carsByBrand: [], carsByFuelType: [] });
  useEffect(() => { api.get('/dashboard/summary').then(r => setSummary(r.data)); api.get('/dashboard/charts').then(r => setCharts(r.data)); }, []);
  return <div className="space-y-4"><div className="grid md:grid-cols-3 gap-4">{Object.entries(summary).map(([k,v]) => <KpiCard key={k} title={k} value={v} />)}</div><div className="grid md:grid-cols-2 gap-4"><ChartCard title="Cars by Brand" data={charts.carsByBrand} /><ChartCard title="Cars by Fuel Type" data={charts.carsByFuelType} /></div></div>;
}
