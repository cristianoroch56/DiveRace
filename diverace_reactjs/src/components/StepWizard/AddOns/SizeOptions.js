import React, { useEffect, useState } from "react";
import ReactModal from "react-modal";
import { updateForm } from "../../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";


const stateType = "pax";
const SizeOptions = (props) => {



    const dispatch = useDispatch();
    const pax_state = useSelector((state) => state.StepWizardReducer.pax);
    const rental_equipment_list = useSelector((state) => state.StepWizardReducer.rental_equipment_list);

    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "100%",
            maxWidth: "800px",
            borderRadius: '10px',
            borderWidth: '0px',
        },
    };

    const updateSize = (size) => {
  
        const { pax, rental } = props.propsData;
    
        const local_pax = [...pax_state];
        const selectedEquipments = local_pax[pax].equipments;
        const filteredEquipments = selectedEquipments.filter(p => p.id != rental);

        const foundequipment = rental_equipment_list.find((eq) => {
            return eq.id == rental;
        });

        const newEquipment = {
            id: foundequipment.id,
            size: size,
            title: foundequipment.title,
            price: foundequipment.price,
        }
        local_pax[pax].equipments = [...filteredEquipments, newEquipment];
        dispatch(updateForm(stateType, local_pax));

        props.closePopup();
    }

    return (
        <ReactModal
            isOpen={props.isOpen}
            style={customStyles}
            contentLabel="Sizeguide Modal"
            ariaHideApp={false}
            onRequestClose={props.closePopup}
        >
            <button onClick={props.closePopup} className="custom-popup-close">X</button>
            <h4 className="text-center mt-5 size__title">Choose a size</h4>
            <div className="row">

                {props.options.length > 0 ? (
                    props.options.map((size, i) =>
                        <div class="col-md-12" key={i}>
                            <button type="button" class="btn btn--bare justify-start size_selection_" onClick={() => updateSize(size)}>
                                <h4 class="">{size}</h4>
                            </button>
                        </div>)
                ) : 'No Size Available'}

            </div>
        </ReactModal >
    );
};

export default SizeOptions;
