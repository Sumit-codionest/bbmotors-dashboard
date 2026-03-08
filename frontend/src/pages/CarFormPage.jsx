import { useForm } from 'react-hook-form';
import { z } from 'zod';
import api from '../api/client';
import { useState } from 'react';

const schema = z.object({
  company_id: z.coerce.number().int().positive(),
  model_id: z.coerce.number().int().positive(),
  registration_no: z.string().min(3),
  make_year: z.coerce.number().int().min(1990).max(new Date().getFullYear() + 1),
  registration_year: z.coerce.number().int().min(1990).max(new Date().getFullYear() + 1),
  km_driven: z.coerce.number().int().nonnegative(),
  price: z.coerce.number().positive(),
  color_code: z.string().min(2),
  status_code: z.string().min(2),
  fuel_type_code: z.string().min(2),
  transmission_code: z.string().min(2)
});

export default function CarFormPage() {
  const { register, handleSubmit, reset } = useForm();
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const onSubmit = async (values) => {
    setError('');
    setSuccess('');
    const parsed = schema.safeParse(values);
    if (!parsed.success) {
      setError(parsed.error.issues[0]?.message || 'Invalid form input');
      return;
    }
    await api.post('/cars', parsed.data);
    setSuccess('Car created successfully');
    reset();
  };

  return <form className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-4 rounded shadow grid md:grid-cols-3 gap-3" onSubmit={handleSubmit(onSubmit)}>
    {error ? <p className="md:col-span-3 text-red-500 text-sm">{error}</p> : null}
    {success ? <p className="md:col-span-3 text-green-600 text-sm">{success}</p> : null}
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="company_id" {...register('company_id')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="model_id" {...register('model_id')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="registration_no" {...register('registration_no')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="make_year" {...register('make_year')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="registration_year" {...register('registration_year')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="km_driven" {...register('km_driven')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="price" {...register('price')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="color_code" {...register('color_code')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="status_code" {...register('status_code')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="fuel_type_code" {...register('fuel_type_code')} />
    <input className="border p-2 bg-white dark:bg-slate-800" placeholder="transmission_code" {...register('transmission_code')} />
    <button className="bg-blue-600 text-white px-3 py-2 rounded">Save</button>
  </form>;
}
