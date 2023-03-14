import DangerButton from '@/Components/DangerButton';
import { useState } from 'react';
//import { useForm } from '@inertiajs/react';

export default function ConfigPlan({className, auth}) {

    const [processing, setProcessing] = useState(false);

    const subscription_id = auth.user.tenant.subscription.subscription_id;

    // const {
    //     data,
    //     setData,
    //     post,
    //     processing,
    //     reset,
    //     errors,
    // } = useForm({
    //     subscription_id: auth.user.tenant.subscription.subscription_id
    // });

    const handleCancelPlan = () => {
        //alert(data.subscription_id);
        // post(route('plan.cancel'), {
        //     //preserveScroll: true,
        //     onSuccess: (data) => { alert('foi') },
        //     onError: () => { alert('erro') },
        //     onFinish: () => {},
        // });
        setProcessing(true);
        axios.post(route('plan.cancel'), {subscription_id}).then(result => {
            setProcessing(false);
            alert(result.data.message);
        }).catch(err => console.log(err));
    }

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">Plano</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Você está no plano <strong>{ auth.user.tenant.subscription.plan.name }</strong>
                </p>

                <DangerButton className="mt-3" processing={processing} onClick={handleCancelPlan}>
                    Desejo cancelar meu plano
                </DangerButton>
            </header>
        </section>
    )

}