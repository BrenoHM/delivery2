import ClientScreen from '@/Layouts/ClientScreen';
import { Head } from '@inertiajs/react';

export default function Dashboard(props) {
    console.log(props.auth.user);
    return (
        <ClientScreen {...props}>
            <Head title="Dashboard Cliente" />

            <h1>Dashboard do cliente</h1>
        </ClientScreen>
    );
}