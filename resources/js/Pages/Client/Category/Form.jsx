import PrimaryButton from '@/Components/PrimaryButton';
import { Link } from '@inertiajs/react';

export default function Form({submit, onChangeField, errors, processing, data, action, props}) {
    const {primaryColor, secondaryColor} = props.auth.user;
    
    return(
        <form className="w-full max-w-2xl" onSubmit={submit} encType="multipart/form-data">
            <div className="flex flex-wrap -mx-3 mb-6">
                <div className="w-full px-3 mb-6 md:mb-0">
                    <label className="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" htmlFor="categorie">
                        Nome
                    </label>
                    <input
                        className={`appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white ${errors.categorie && 'border-red-500'}`}
                        id="categorie"
                        type="text"
                        placeholder="Ex. Pizzas"
                        onChange={e => onChangeField('categorie', e.target.value)}
                        value={data?.categorie}
                    />
                    {errors.categorie && <p className="text-red-500 text-xs italic">{errors.categorie}</p>}
                </div>
            </div>
            <div className="text-center">
                <PrimaryButton className="mr-2" processing={processing} style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}>{action ?? 'Salvar'}</PrimaryButton>
                <Link href={route('category.index')} className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">Cancelar</Link>
            </div>
        </form>
    )
}