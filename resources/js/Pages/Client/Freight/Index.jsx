import ClientScreen from '@/Layouts/ClientScreen';
import { Head, Link, usePage } from '@inertiajs/react';
import DataTable from 'react-data-table-component';
import { BiTrash, BiPencil } from "react-icons/bi";
import { useState, useEffect, useMemo } from 'react';
import {modalExcluded, paginationComponentOptions} from '@/helper';
import FilterComponent from '@/Components/FilterComponent';

export default function Index(props) {

    const {flash} = usePage().props
    const {primaryColor, secondaryColor} = props.auth.user;
    const [data, setData] = useState([]);
	const [loading, setLoading] = useState(false);
	const [totalRows, setTotalRows] = useState(0);
	const [perPage, setPerPage] = useState(10);
    const [actualPage, setActualPage] = useState(1);

    const [filterText, setFilterText] = useState('');
	const [resetPaginationToggle, setResetPaginationToggle] = useState(false);

	const filteredItems = data.filter(
		item => item.neighborhood && item.neighborhood.toLowerCase().includes(filterText.toLowerCase()),
	);

    const handleButtonDelete = (e, id) => {
        e.preventDefault();
        modalExcluded(async () => {
            const result = await axios.delete(route('freight.destroy', id));
            fetchRows(actualPage);
        });
    }

	const fetchRows = async (page) => {

        setLoading(true);

        const response = await axios.get(route('freight.index', {json: true, page, per_page: perPage, term: filterText}));

		setData(response.data.data);
		setTotalRows(response.data.total);
        setActualPage(page);
		setLoading(false);
	};

    const handlePageChange = page => {
		fetchRows(page);
	};

	const handlePerRowsChange = async (newPerPage, page) => {
		setLoading(true);

        const response = await axios.get(route('freight.index', {json: true, page, per_page: newPerPage, term: filterText}));

		setData(response.data.data);
		setPerPage(newPerPage);
        setActualPage(page);
		setLoading(false);
	};

    const subHeaderComponentMemo = useMemo(() => {
		const handleClear = () => {
			if (filterText) {
				setResetPaginationToggle(!resetPaginationToggle);
				setFilterText('');
			}
		};

		return (
			<FilterComponent onFilter={e => setFilterText(e.target.value)} onClear={handleClear} filterText={filterText} />
		);
	}, [filterText, resetPaginationToggle]);

    useEffect(() => {
        fetchRows(actualPage);
	}, [filterText]);

    const columns = [
        {
            name: 'Id',
            selector: row => row.id,
        },
        {
            name: 'Bairro',
            selector: row => row.neighborhood,
        },
        {
            name: 'Cidade',
            selector: row => row.city,
        },
        {
            name: 'Estado',
            selector: row => row.state,
        },
        {
            name: 'Preço',
            selector: row => row.price,
        },
        {		
            cell: (row) => <Link href={route('freight.edit', row.id)}><BiPencil size={20} /></Link>,
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
        },
        {		
            cell: (row) => <Link onClick={(e) => handleButtonDelete(e, row.id)}><BiTrash size={20} /></Link>,
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
        },
    ];

    return (
        <ClientScreen {...props}>
            <Head title="Listagem de Fretes" />

            <Link
                style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}
                className={`${props.color && `bg-[${props.color}]`} text-white py-2 px-3 inline-block mb-3 float-right`}
                href={route('freight.create')}>Novo Frete
            </Link>

            {props.flash.message && (
                <div className="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3 clear-both mb-3" role="alert">
                    <p className="font-bold">Mensagem</p>
                    <p className="text-sm">{flash.message}</p>
                </div>
            )}

            <DataTable
                title="Fretes Cadastrados"
                columns={columns}
                data={filteredItems}
                progressPending={loading}
                pagination
                paginationComponentOptions={paginationComponentOptions}
                paginationServer
                paginationTotalRows={totalRows}
                onChangeRowsPerPage={handlePerRowsChange}
                onChangePage={handlePageChange}
                striped
                subHeader
                subHeaderComponent={subHeaderComponentMemo}
                paginationResetDefaultPage={resetPaginationToggle}
            />            
        </ClientScreen>
    );
}