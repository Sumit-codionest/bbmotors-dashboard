import { useForm } from 'react-hook-form';
import { z } from 'zod';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { useState } from 'react';

const schema = z.object({ username: z.string().min(3), password: z.string().min(6) });

export default function LoginPage() {
  const { login } = useAuth();
  const nav = useNavigate();
  const [error, setError] = useState('');
  const { register, handleSubmit } = useForm();

  const onSubmit = async (values) => {
    setError('');
    const parsed = schema.safeParse(values);
    if (!parsed.success) {
      setError(parsed.error.issues[0]?.message || 'Invalid form input');
      return;
    }
    try {
      await login(parsed.data.username, parsed.data.password);
      nav('/');
    } catch {
      setError('Login failed');
    }
  };

  return (
    <div className="min-h-screen grid place-items-center">
      <form className="bg-white dark:bg-slate-900 dark:border dark:border-slate-700 p-8 rounded shadow w-96" onSubmit={handleSubmit(onSubmit)}>
        <h2 className="text-xl mb-4">Login</h2>
        {error ? <p className="text-red-500 text-sm mb-2">{error}</p> : null}
        <input className="w-full border p-2 mb-3 bg-white dark:bg-slate-800" placeholder="Username" {...register('username')} />
        <input className="w-full border p-2 mb-3 bg-white dark:bg-slate-800" type="password" placeholder="Password" {...register('password')} />
        <button className="w-full bg-blue-600 text-white py-2 rounded">Sign In</button>
      </form>
    </div>
  );
}
