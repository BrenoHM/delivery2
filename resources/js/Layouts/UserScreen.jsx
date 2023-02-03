import React from "react";
import Authenticated from "@/Layouts/AuthenticatedLayout";
import { Head } from '@inertiajs/react';

export default function UserScreen(props) {
  return (
    <Authenticated
      auth={props.auth}
      errors={props.errors}
      header={
        props.header || <h2 className="text-xl font-semibold leading-tight text-gray-800">
          Ambiente do Usuário
        </h2>
      }
    >
      {/* <Head title="" /> */}

      <div className="grid sm:grid-cols-1 md:grid-cols-[400px_1fr]">
        <div className="w-100">
            <div className="py-9">
                <div className="sm:pr-6 md:pl-6 md:pr-0">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h2 className="mb-3 bg-black text-white p-3 font-bold">Menu</h2>
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
