import ClientScreen from '@/Layouts/ClientScreen';
import { Head, Link, usePage } from '@inertiajs/react';
import DataTable from 'react-data-table-component';
import { BiTrash, BiPencil } from "react-icons/bi";
import { useState, useEffect, useMemo } from 'react';
import { modalExcluded, CustomLoader } from '@/helper';
import FilterComponent from '@/Components/FilterComponent';
import OrderDetail from '@/Components/OrderDetail';

export default function Index(props) {

    const {flash} = usePage().props
    const {primaryColor, secondaryColor} = props.auth.user.tenant;
    const [data, setData] = useState([]);
	const [loading, setLoading] = useState(false);
	const [totalRows, setTotalRows] = useState(0);
	const [perPage, setPerPage] = useState(10);
    const [actualPage, setActualPage] = useState(1);

    const [filterText, setFilterText] = useState('');
	const [resetPaginationToggle, setResetPaginationToggle] = useState(false);

	// const filteredItems = data.filter(
	// 	item => item.id == filterText,
	// );

    const filteredItems = filterText ? data.filter(item => item.id == filterText) : data;

    const handleButtonDelete = (e, id) => {
        e.preventDefault();
        modalExcluded(async () => {
            const result = await axios.delete(route('products.destroy', id));
            fetchProducts(actualPage);
        });
    }

	const fetchProducts = async (page) => {

        setLoading(true);

        const response = await axios.get(route('order.index', {json: true, page, per_page: perPage, term: filterText}));

		setData(response.data);
        console.log(filteredItems);
		setTotalRows(response.data.total);
        setActualPage(page);
		setLoading(false);
	};

    // const handlePageChange = page => {
	// 	fetchProducts(page);
	// };

	// const handlePerRowsChange = async (newPerPage, page) => {
	// 	setLoading(true);

    //     const response = await axios.get(route('client.products', {json: true, page, per_page: newPerPage, term: filterText}));

	// 	setData(response.data.data);
	// 	setPerPage(newPerPage);
    //     setActualPage(page);
	// 	setLoading(false);
	// };

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
        fetchProducts(actualPage); // fetch page 1 of products
	}, []);

    const columns = [
        // {
        //     name: 'Foto',
        //     selector: row => <img width={80} src={row.photo} />,
        // },
        {
            name: 'Pedido N°',
            selector: row => '#'+row.id,
        },
        {
            name: 'Nome',
            selector: row => row.name,
        },
        // {
        //     name: 'Categoria',
        //     selector: row => row.category.categorie,
        // },
        {
            name: 'Total',
            selector: row => row.total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}),
        },
        {		
            //cell: (row) => <button onClick={() => handleButtonClick(row.id)}><BiPencil size={20} /></button>,
            cell: (row) => <Link href={route('products.edit', row.id)}><BiPencil size={20} /></Link>,
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
        },
        {		
            //cell: (row) => <button onClick={() => handleButtonClick(row.id)}><BiTrash size={20} /></button>,
            cell: (row) => <Link onClick={(e) => handleButtonDelete(e, row.id)}><BiTrash size={20} /></Link>,
            ignoreRowClick: true,
            allowOverflow: true,
            button: true,
        },
    ];

    // const paginationComponentOptions = {
    //     rowsPerPageText: 'Filas por página',
    //     rangeSeparatorText: 'de',
    //     selectAllRowsItem: true,
    //     selectAllRowsItemText: 'Todos',
    // };

    //const ExpandedComponent = ({ data }) => <pre>{JSON.stringify(data, null, 2)}</pre>;

    return (
        <ClientScreen {...props}>
            <Head title="Listagem de Produtos" />

            <DataTable
                title="Pedidos"
                noDataComponent="Não há pedidos para exibir"
                columns={columns}
                data={filteredItems}
                // progressPending={loading}
                // pagination
                // paginationComponentOptions={paginationComponentOptions}
                // paginationServer
                // paginationTotalRows={totalRows}
                // onChangeRowsPerPage={handlePerRowsChange}
                // onChangePage={handlePageChange}
                striped
                subHeader
                subHeaderComponent={subHeaderComponentMemo}
                paginationResetDefaultPage={resetPaginationToggle}
                progressComponent={<CustomLoader />}
                expandableRows
                expandableRowsComponent={OrderDetail}
            />            
        </ClientScreen>
    );
}