import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Link, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';
import { slugify, dayOfWeek } from '@/helper';
import InputMask from 'react-input-mask';
import { useState } from 'react';
import cep from 'cep-promise';
import Swal from 'sweetalert2';
import TimeField from 'react-simple-timefield';

export default function UpdateProfileInformation({ mustVerifyEmail, status, className }) {
    const user = usePage().props.auth.user;
    const {app_url} = usePage().props;

    const [cepField, setCep] = useState(user.tenant?.zip_code ?? "");
    const [street, setStreet] = useState(user.tenant?.street ?? "");
    //const [number, setNumber] = useState(user.tenant?.number ?? "");
    const [neighborhood, setNeighborhood] = useState(user.tenant?.neighborhood ?? "");
    const [state, setState] = useState(user.tenant?.state ?? "");
    const [city, setCity] = useState(user.tenant?.city ?? "");
    const [timeline, setTimeline] = useState(user.tenant?.timelines.length > 0 ? user.tenant?.timelines : [
        {day_of_week: 1, start: "", end: ""},
        {day_of_week: 2, start: "", end: ""},
        {day_of_week: 3, start: "", end: ""},
        {day_of_week: 4, start: "", end: ""},
        {day_of_week: 5, start: "", end: ""},
        {day_of_week: 6, start: "", end: ""},
        {day_of_week: 7, start: "", end: ""}
    ]);

    if( user.tenant ){
        const {primaryColor, secondaryColor} = user.tenant;
    }

    const { data, setData, transform, post, errors, processing, recentlySuccessful } = useForm({
        id: user.id,
        name: user.name,
        email: user.email,
        primaryColor: user.tenant?.primaryColor ?? "#555555",
        secondaryColor: user.tenant?.secondaryColor ?? "#555555",
        domain: user.tenant?.domain,
        tenant_id: user.tenant?.id ?? "",
        zip_code: user.tenant?.zip_code ?? "",
        street: user.tenant?.street ?? "",
        neighborhood: user.tenant?.neighborhood ?? "",
        number: user.tenant?.number ?? "",
        city: user.tenant?.city ?? "",
        state: user.tenant?.state ?? "",
        timeline: user.tenant?.timelines.length > 0 ? user.tenant?.timelines : "",
        type_pix_key: user.tenant?.type_pix_key ?? "",
        pix_key: user.tenant?.pix_key ?? "",
        logo: ''
    });

    //before send data
    transform((data) => ({
        ...data,
        domain: user.role == 'client' ? slugify(data.name) : '',
        zip_code: cepField,
        street: street,
        neighborhood: neighborhood,
        //number: number,
        city: city,
        state: state,
        timeline: timeline
    }));

    const submit = (e) => {
        e.preventDefault();

        post(route('profile.update'));
    };

    const searchCep = value => {
        setCep(value);
        if( value.length == 8 ) {
            cep(value)
                .then(async data => {
                    setStreet(data.street);
                    setNeighborhood(data.neighborhood);
                    setCity(data.city);
                    setState(data.state);
                    setData('street', data.street);
                    setData('neighborhood', data.neighborhood);
                    setData('city', data.city);
                    setData('state', data.state);
                })
                .catch(err => {
                    setCep('');
                    setStreet('');
                    setData('number', '');
                    setNeighborhood('');
                    setCity('');
                    setState('');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: err.message,
                        //footer: '<a href="">Why do I have this issue?</a>'
                    })
                })
        }
    }

    const changeTimeline = (value, field, index) => {
        let aux = timeline;
        aux[index][field] = value;
        setTimeline(aux);
    }

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">Informação do Perfil</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Atualize as informações de perfil e o endereço de e-mail da sua conta.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6" encType="multipart/form-data">
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
                    <>
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
                        </div>

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
                        </div>

                        <header>
                            <h2 className="text-lg font-medium text-gray-900">Endereço do estabelecimento</h2>

                            <p className="mt-1 text-sm text-gray-600">
                                Atualize as informações do endereço do seu estabelecimento.
                            </p>
                        </header>

                        <div>
                            <InputLabel forInput="zip_code" value="Cep" />

                            <InputMask
                                mask="99999999"
                                maskChar={null}
                                className={`border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm`}
                                id="zip_code"
                                type="text"
                                placeholder="Ex. 05010000"
                                //onChange={e => onChangeField('addition', e.target.value)}
                                onChange={e => searchCep(e.target.value)}
                                value={cepField}
                            />
                        </div>

                        <div>
                            <InputLabel forInput="street" value="Rua" />

                            <TextInput
                                id="street"
                                type="text"
                                className="mt-1 block w-full disabled:bg-gray-100 disabled:cursor-not-allowed"
                                value={street}
                                disabled={true}
                            />

                            <InputError className="mt-2" message={errors.street} />
                        </div>

                        <div>
                            <InputLabel forInput="number" value="Número" />

                            <TextInput
                                id="number"
                                type="number"
                                className="mt-1 block w-1/4"
                                value={data.number}
                                handleChange={(e) => setData('number', e.target.value)}
                            />

                            <InputError className="mt-2" message={errors.number} />
                        </div>

                        <div>
                            <InputLabel forInput="neighborhood" value="Bairro" />

                            <TextInput
                                id="neighborhood"
                                type="text"
                                className="mt-1 block w-full disabled:bg-gray-100 disabled:cursor-not-allowed"
                                value={neighborhood}
                                disabled={true}
                            />

                            <InputError className="mt-2" message={errors.neighborhood} />
                        </div>

                        <div>
                            <InputLabel forInput="city" value="Cidade" />

                            <TextInput
                                id="city"
                                type="text"
                                className="mt-1 block w-full disabled:bg-gray-100 disabled:cursor-not-allowed"
                                value={city}
                                disabled={true}
                            />

                            <InputError className="mt-2" message={errors.city} />
                        </div>

                        <div>
                            <InputLabel forInput="state" value="Estado" />

                            <TextInput
                                id="state"
                                type="text"
                                className="mt-1 block w-1/4 disabled:bg-gray-100 disabled:cursor-not-allowed"
                                value={state}
                                disabled={true}
                            />

                            <InputError className="mt-2" message={errors.state} />
                        </div>

                        <header>
                            <h2 className="text-lg font-medium text-gray-900">Cronograma</h2>

                            <p className="mt-1 text-sm text-gray-600">
                                Atualize o cronograma de funcionamento do seu estabelecimento.
                            </p>
                        </header>

                        {timeline.map((time, i) => (
                            <div key={timeline[i].day_of_week}>
                                
                                <InputLabel forInput={timeline[i].day_of_week} value={dayOfWeek[timeline[i].day_of_week]} className="mb-1" />
                                
                                <TimeField
                                    className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    style={{width:'70px'}}
                                    id={timeline[i].day_of_week}
                                    value={timeline[i].start}
                                    onChange={(e) => changeTimeline(e.target.value, 'start', i)}
                                    colon=":"
                                />

                                <span className='mx-1'>até</span>

                                <TimeField
                                    className="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    style={{width:'70px'}}
                                    value={timeline[i].end}
                                    onChange={(e) => changeTimeline(e.target.value, 'end', i)}
                                    colon=":"
                                />

                            </div>
                        ))}

                        <header>
                            <h2 className="text-lg font-medium text-gray-900">Chave Pix</h2>

                            <p className="mt-1 text-sm text-gray-600">
                                Configure sua chave caso queira receber via pix.
                            </p>
                        </header>

                        <div>
                            <InputLabel forInput="type_pix_key" value="Tipo Chave" />

                            <select
                                id="type_pix_key"
                                className="mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                onChange={(e) => setData('type_pix_key', e.target.value)}
                                value={data.type_pix_key}
                            >
                                <option value="">Nenhum</option>
                                <option value="cpf">CPF</option>
                                <option value="email">Email</option>
                                <option value="phone">Telefone</option>
                                <option value="random">Aleatória</option>
                            </select>

                            <InputError className="mt-2" message={errors.type_pix_key} />
                        </div>

                        <div>
                            <InputLabel forInput="pix_key" value="Valor Chave" />

                            <TextInput
                                id="pix_key"
                                type="text"
                                className="mt-1 block w-full"
                                value={data.pix_key}
                                handleChange={(e) => setData('pix_key', e.target.value)}
                            />

                            <InputError className="mt-2" message={errors.pix_key} />
                        </div>

                        <div>
                            <InputLabel forInput="logo" value="Logomarca" />

                            <TextInput
                                id="logo"
                                type="file"
                                handleChange={(e) => setData('logo', e.target.files[0])}
                            />

                            <InputError className="mt-2" message={errors.logo} />
                        </div>
                            
                    </>
                
                )}

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
