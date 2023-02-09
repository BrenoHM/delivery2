import ClientScreen from '@/Layouts/ClientScreen';
import UserScreen from '@/Layouts/UserScreen';
import AdminScreen from '@/Layouts/AdminScreen';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import { Head } from '@inertiajs/react';

export  default function Edit({ auth, mustVerifyEmail, status, app_url }) {
    if(auth.user.role == 'client' ){

        return (
            // <AuthenticatedLayout
            //     auth={auth}
            //     header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Perfil</h2>}
            // >
            <ClientScreen
                auth={auth}
                //header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Perfil Cliente</h2>} //optional
            >
                <Head title="Perfil" />
    
                <div>
                    <div className="sm:px-6 lg:px-8 space-y-6">
                        <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <UpdateProfileInformationForm
                                mustVerifyEmail={mustVerifyEmail}
                                status={status}
                                appUrl={app_url}
                                className="max-w-xl"
                            />
                        </div>
    
                        <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <UpdatePasswordForm className="max-w-xl" />
                        </div>
    
                        <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                            <DeleteUserForm className="max-w-xl" />
                        </div>
                    </div>
                </div>
            </ClientScreen>
            // <AuthenticatedLayout />
        );

    }else if(auth.user.role == 'user' ){
        return (
        <UserScreen
            auth={auth}
            //header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Perfil Usuário</h2>}
        >
            <Head title="Perfil" />

            <div className="sm:px-6 lg:px-8 space-y-6">
                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        mustVerifyEmail={mustVerifyEmail}
                        status={status}
                        className="max-w-xl"
                    />
                </div>

                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm className="max-w-xl" />
                </div>

                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm className="max-w-xl" />
                </div>
            </div>
            
        </UserScreen>)
    }else if(auth.user.role == 'admin' ){
        return (
        <AdminScreen
            auth={auth}
            //header={<h2 className="text-xl font-semibold leading-tight text-gray-800">Perfil Admin</h2>}
        >
            <Head title="Perfil" />

            <div className="sm:px-6 lg:px-8 space-y-6">
                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        mustVerifyEmail={mustVerifyEmail}
                        status={status}
                        className="max-w-xl"
                    />
                </div>

                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm className="max-w-xl" />
                </div>

                <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm className="max-w-xl" />
                </div>
            </div>
            
        </AdminScreen>)
    }
    
}
