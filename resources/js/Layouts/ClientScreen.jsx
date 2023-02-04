import React from "react";
import Authenticated from "@/Layouts/AuthenticatedLayout";
import NavLink from "@/Components/NavLink";
import { 
  RiTruckLine,
  RiDashboardLine,
  RiAddCircleLine,
  RiShoppingCart2Line,
  RiGridFill
} from "react-icons/ri";

export default function ClientScreen(props) {
  
  const {primaryColor, secondaryColor} = props.auth.user;
  
  return (
    <Authenticated
      auth={props.auth}
      errors={props.errors} 
      header={
        props.header || <h2 className="text-xl font-semibold leading-tight text-gray-800">
          Ambiente do Cliente
        </h2>
      }
    >
      <div className="grid sm:grid-cols-1 md:grid-cols-[350px_1fr]">
        <div className="w-100">
            <div className="py-9">
                <div className="sm:pr-6 md:pl-6 md:pr-0">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h2 className="mb-3 text-white p-3 font-bold" style={{background: primaryColor ? primaryColor : props.defaultPrimaryColor}}>Menu Cliente</h2>
                            <div className="mb-5">
                              <NavLink href={route('clientDashboard')} active={route().current('clientDashboard')}>
                                <RiDashboardLine size={20} className="mr-2" /> Dashboard
                              </NavLink>
                            </div>
                            <div className="mb-5">
                              <NavLink href={route('client.products')} active={route().current('client.products') || route().current('products.create') || route().current('products.edit')}>
                                <RiShoppingCart2Line size={20} className="mr-2" /> Produtos
                              </NavLink>
                            </div>
                            <div className="mb-5">
                              <NavLink href={route('category.index')} active={route().current('category.index') || route().current('category.create') || route().current('category.edit')}>
                                <RiGridFill size={20} className="mr-2" /> Categorias
                              </NavLink>
                            </div>
                            <div className="mb-5">
                              <NavLink href={route('client.addition')} active={route().current('client.addition')}>
                                <RiAddCircleLine size={20} className="mr-2" /> Acréscimos
                              </NavLink>
                            </div>
                            <div>
                              <NavLink href={route('client.freight')} active={route().current('client.freight')}>
                                <RiTruckLine size={20} className="mr-2" /> Frete
                              </NavLink>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div className="basis-full">
          <div className="py-9">
            <div className="sm:px-6 lg:px-8">
              <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="p-6 bg-white border-b border-gray-200">{props.children}</div>
              </div>
            </div>  
          </div>
        </div>
      </div>
    </Authenticated>
  );
}
