import React, { useEffect, useState } from "react";
import ReactModal from "react-modal";
import { useDispatch, useSelector } from "react-redux";
import {

    updateForm,
  } from "../../store/actions/StepWizardAction";

const CurencyModal = (props) => {



    const dispatch = useDispatch();
    const final_payment_amount = useSelector((state) => state.StepWizardReducer.final_payble_amount);
    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);

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

    const sgdToUsd = () => {
        const USD = final_payment_amount*0.74278;
        dispatch(updateForm('final_payble_amount', parseFloat(USD).toFixed(2)))
        //dispatch(updateForm('total_cost_amount', parseFloat(USD).toFixed(2)))
    }
    
    const usdToSgd = () => {
        const SGD = final_payment_amount*1.3462;
        dispatch(updateForm('final_payble_amount', parseFloat(SGD).toFixed(2)))
        //dispatch(updateForm('total_cost_amount', parseFloat(SGD).toFixed(2)))
    }

    const updateCurency = (label, code) => {

        
        if(label === 'SGD' && default_currency != 'SGD') {
            usdToSgd();
        } else if(label === 'USD' && default_currency != 'USD') {
            sgdToUsd();
        }
        dispatch(updateForm('default_currency',label));
        dispatch(updateForm('default_currency_code',code));

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
            <h4 className="text-center mt-5 size__title">Choose a Currency</h4>
            <div className="row">

                <div class="col-md-12" >
                    <button type="button" class="btn btn--bare justify-start size_selection_" onClick={() => updateCurency('SGD','S$')}>
                        <h4 className="">SGD - S$</h4>
                    </button>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn--bare justify-start size_selection_" onClick={() => updateCurency('USD','$')}>
                        <h4 className="">USD - $</h4>
                    </button>
                </div>
            </div>
        </ReactModal >
    );
};

export default CurencyModal;
