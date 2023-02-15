import ClientScreen from '@/Layouts/ClientScreen';
import Form from '@/Pages/Client/Freight/Form';
import { Head, useForm } from '@inertiajs/react';

export default function Create(props) {

    const { data, setData, post, processing, errors } = useForm({
        neighborhood: '',
        city: '',
        state: '',
        price: '',
        tenant_id: props.auth.user.tenant_id
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('freight.store'));
    }

    const onChangeField = (field, value) => {
        setData(field, value);
    }

    const onChangeFieldAll = (obj) => {
        setData({
            neighborhood: obj.neighborhood,
            city: obj.city,
            state: obj.state,
            price: data.price,
            tenant_id: props.auth.user.tenant_id
        });
    }

    return (
        <ClientScreen {...props}>
            <Head title="Cadastrar Frete" />
                <h2 className='mb-5'>ADICIONAR FRETE</h2>
                <Form
                    submit={handleSubmit}
                    onChangeField={onChangeField}
                    onChangeFieldAll={onChangeFieldAll}
                    errors={errors}
                    processing={processing}
                    action={props.action}
                    props={props}
                />
        </ClientScreen>
    )
}