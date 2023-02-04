import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';

export default function Form({submit, onChangeField, errors, processing, data, action, props}) {
    const {primaryColor, secondaryColor} = props.auth.user;
    
    return(
        <form className="w-full max-w-2xl" onSubmit={submit} encType="multipart/form-data">
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="addition">
                        Acréscimo
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.addition && 'border-red-500'}`}
                        id="addition"
                        type="text"
                        placeholder="Ex. Bacon"
                        onChange={e => onChangeField('addition', e.target.value)}
                        value={data?.addition}
                    />
                    {errors.addition && <p className="text-red-500 text-xs italic">{errors.addition}</p>}
                </div>
            </div>
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="price">
                        Preço
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.price && 'border-red-500'}`}
                        id="price"
                        type="number"
                        onChange={e => onChangeField('price', e.target.value)}
                        value={data?.price}
                        min="1"
                        step=".01"
                    />
                    {errors.price && <p className="text-red-500 text-xs italic">{errors.price}</p>}
                </div>
            </div>
            <div className="text-center">
                <PrimaryButton className="mr-2" processing={processing} style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}>{action ?? 'Salvar'}</PrimaryButton>
                <Link href={route('addition.index')} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</Link>
            </div>
        </form>
    )
}