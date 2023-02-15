import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Freight/Form';
import { Head, useForm } from '@inertiajs/react';

export default function Create(props) {

    const { data, setData, put, processing, errors } = useForm({
        id: props.freight.id,
        neighborhood: props.freight.neighborhood,
        city: props.freight.city,
        state: props.freight.state,
        price: props.freight.price,
        tenant_id: props.auth.user.tenant_id
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        put(route('freight.update', props.freight.id));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    return (
        <ClientScreen {...props}>
            <Head title="Editar Frete" />
                <h2 className='mb-5'>EDITAR FRETE</h2>
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