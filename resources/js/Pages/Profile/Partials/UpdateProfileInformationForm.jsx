import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
import { slugify } from '@/helper';

export default function UpdateProfileInformation({ mustVerifyEmail, status, className }) {
    const user = usePage().props.auth.user;
    const {app_url} = usePage().props;

    if( user.tenant ){
        const {primaryColor, secondaryColor} = user.tenant;
    }

    const { data, setData, transform, patch, errors, processing, recentlySuccessful } = useForm({
        id: user.id,
        name: user.name,
        email: user.email,
        primaryColor: user.tenant?.primaryColor ?? "",
        secondaryColor: user.tenant?.secondaryColor ?? "",
        domain: user.tenant?.domain,
        tenant_id: user.tenant?.id ?? ""
    });

    //before send data
    transform((data) => ({
        ...data,
        domain: user.role == 'client' ? slugify(data.name) : '',
    }));

    const submit = (e) => {
        e.preventDefault();

        patch(route('profile.update'));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">Informação do Perfil</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Atualize as informações de perfil e o endereço de e-mail da sua conta.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel forInput="name" value="Nome" />

                    <TextInput
                        id="name"
                        className="mt-1 block w-full"
                        value={data.name}
                        handleChange={(e) => setData('name', e.target.value)}
                        required
                        isFocused
                        autoComplete="name"
                    />
                    {user.role == 'client' && (<i><small>Url do seu site: <strong>{`http://${user.tenant.domain}.${app_url}`}</strong></small></i>)}

                    <InputError className="mt-2" message={errors.name} />

                    <InputError className="mt-2" message={errors.domain} />

                </div>

                <div>
                    <InputLabel forInput="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        className="mt-1 block w-full"
                        value={data.email}
                        handleChange={(e) => setData('email', e.target.value)}
                        required
                        autoComplete="email"
                    />

                    <InputError className="mt-2" message={errors.email} />
                </div>

                {user.role == 'client' && (
                    <div>
                        <InputLabel forInput="primaryColor" value="Cor Primária" />

                        <TextInput
                            id="primaryColor"
                            type="color"
                            className="mt-1 block w-full"
                            value={data.primaryColor}
                            handleChange={(e) => setData('primaryColor', e.target.value)}
                            autoComplete="primaryColor"
                        />

                        <InputError className="mt-2" message={errors.primaryColor} />
                </div>)}

                {user.role == 'client' && (
                    <div>
                        <InputLabel forInput="secondaryColor" value="Cor Secundária" />

                        <TextInput
                            id="secondaryColor"
                            type="color"
                            className="mt-1 block w-full"
                            value={data.secondaryColor}
                            handleChange={(e) => setData('secondaryColor', e.target.value)}
                            autoComplete="secondaryColor"
                        />

                        <InputError className="mt-2" message={errors.secondaryColor} />
                </div>)}

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div>
                        <p className="text-sm mt-2 text-gray-800">
                            Your email address is unverified.
                            <Link
                                href={route('verification.send')}
                                method="post"
                                as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        {status === 'verification-link-sent' && (
                            <div className="mt-2 font-medium text-sm text-green-600">
                                A new verification link has been sent to your email address.
                            </div>
                        )}
                    </div>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton processing={processing} style={{background: user.tenant?.primaryColor ? user.tenant.primaryColor : ''}}>Salvar</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enterFrom="opacity-0"
                        leaveTo="opacity-0"
                        className="transition ease-in-out"
                    >
                        <p className="text-sm text-gray-600">Salvo.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
