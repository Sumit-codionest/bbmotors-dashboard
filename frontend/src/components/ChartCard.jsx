import { BarChart, Bar, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts';

export default function ChartCard({ title, data }) {
  return <div className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow h-80"><h3 className="font-semibold mb-2">{title}</h3><ResponsiveContainer width="100%" height="90%"><BarChart data={data}><XAxis dataKey="name" /><YAxis /><Tooltip /><Bar dataKey="value" fill="#2563eb" /></BarChart></ResponsiveContainer></div>;
}
