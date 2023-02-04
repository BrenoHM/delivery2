import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Addition/Form';
import { Head, useForm } from '@inertiajs/react';

export default function Create(props) {

    const { data, setData, post, processing, errors } = useForm({
        id: props.addition.id,
        addition: props.addition.addition,
        price: props.addition.price,
        user_id: props.auth.user.id
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('addition.update', props.addition.id));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Editar Acréscimo" />
                <h2 className='mb-5'>EDITAR ACRÉSCIMO</h2>
                <Form
                    submit={handleSubmit}
                    onChangeField={onChangeField}
                    errors={errors}
                    processing={processing}
                    data={data}
                    action={props.action}
                    props={props}
                />            
        </ClientScreen>
    )
}