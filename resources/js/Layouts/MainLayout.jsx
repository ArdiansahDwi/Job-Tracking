import { Link, router } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import {
    ChartBarIcon,
    BriefcaseIcon,
    Cog6ToothIcon,
    ArrowRightOnRectangleIcon,
} from '@heroicons/react/24/outline';

const navigation = [
    { name: 'Dashboard', href: '/dashboard', icon: ChartBarIcon },
    { name: 'Job Track', href: '/jobs', icon: BriefcaseIcon },
    { name: 'Settings', href: '/settings', icon: Cog6ToothIcon },
];

export default function MainLayout({ children, title }) {
    const { auth } = usePage().props;
    const user = auth?.user;
    const currentPath = window.location.pathname;

    const handleLogout = () => {
        router.post('/logout');
    };

    return (
        <div className="flex h-screen bg-slate-50">
            {/* Sidebar */}
            <aside className="flex flex-col w-64 bg-slate-900 shadow-xl flex-shrink-0">
                {/* Logo */}
                <div className="flex items-center gap-3 px-6 py-5 border-b border-slate-700">
                    <div className="w-9 h-9 bg-primary-500 rounded-lg flex items-center justify-center">
                        <BriefcaseIcon className="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <p className="text-white font-bold text-base leading-tight">Job Tracker</p>
                        <p className="text-slate-400 text-xs">Pantau lamaranmu</p>
                    </div>
                </div>

                {/* Navigation */}
                <nav className="flex-1 px-4 py-6 space-y-1">
                    {navigation.map((item) => {
                        const isActive = currentPath === item.href || currentPath.startsWith(item.href + '/');
                        return (
                            <Link
                                key={item.name}
                                href={item.href}
                                className={`flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200 ${
                                    isActive
                                        ? 'bg-primary-600 text-white shadow-sm shadow-primary-900'
                                        : 'text-slate-400 hover:bg-slate-800 hover:text-white'
                                }`}
                            >
                                <item.icon className="w-5 h-5 flex-shrink-0" />
                                {item.name}
                            </Link>
                        );
                    })}
                </nav>

                {/* User Profile & Logout */}
                <div className="px-4 py-4 border-t border-slate-700">
                    <div className="flex items-center gap-3 px-2 mb-3">
                        <img
                            src={user?.avatar_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&background=0ea5e9&color=fff`}
                            alt={user?.name}
                            className="w-9 h-9 rounded-full ring-2 ring-slate-700 object-cover"
                        />
                        <div className="min-w-0">
                            <p className="text-white text-sm font-medium truncate">{user?.name}</p>
                            <p className="text-slate-400 text-xs truncate">{user?.email}</p>
                        </div>
                    </div>
                    <button
                        onClick={handleLogout}
                        className="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-slate-400 hover:text-white hover:bg-slate-800 rounded-lg transition-all duration-200"
                    >
                        <ArrowRightOnRectangleIcon className="w-5 h-5" />
                        Logout
                    </button>
                </div>
            </aside>

            {/* Main Content */}
            <div className="flex flex-col flex-1 overflow-hidden">
                {/* Top Bar */}
                <header className="h-16 bg-white border-b border-slate-100 flex items-center px-8 shadow-sm flex-shrink-0">
                    <h1 className="text-xl font-semibold text-slate-800">{title}</h1>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-y-auto p-8">
                    {children}
                </main>
            </div>
        </div>
    );
}
