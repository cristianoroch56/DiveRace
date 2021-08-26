import React, { useEffect, useState, useRef } from "react";
import { useHistory, Link } from "react-router-dom";
import Loaderdiv from "../StepWizard/Loader";
import '../Login/index.css';
import ReactModal from 'react-modal';
import { useSelector } from "react-redux";
import ApiServices from "../../services/index"
import PhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/lib/style.css'

const WaitListModal = (props) => {


    const [inputs, setInputs] = useState({
        first_name: "",
        last_name: "",
        email: "",
        phone_number: "",

    });
    const [waitingProps, setWaitingProps] = useState(props.waitingProps);
    const [apis, setApis] = useState({
        error_msg: ''
    })

    const [isSubmitted, setIsSubmitted] = useState(false);


    const handleSubmit = async (e) => {
        e.preventDefault();

        setIsSubmitted(true);

        var formData = new FormData(document.getElementById("trip_form"));
      
        formData.append(
            "vessel_id", waitingProps.vessel_id
        );
        formData.append(
            "destination_id", waitingProps.destination_id
        );
        formData.append(
            "trip_date_id", waitingProps.trip_date_id
        );
        formData.append(
            "phone_number", inputs.phone_number
        );
        let resp = await ApiServices.addWaitingList(formData);
        setIsSubmitted(false);
        if (resp.data.status) {
            window.$.toast({
                heading: "Waiting list",
                text: "Thanks! You’ve been added to our waiting list.",
                position: 'bottom-right',
                icon: 'info',
                hideAfter: 5000,
                loaderBg: '#ffffff',
            });
            props.closePopup();
        } else {
            setApis({ error_msg: resp.data.message })
        }
    };
    const customStyles = {
        content: {
            top: "50%",
            left: "50%",
            right: "auto",
            bottom: "auto",
            transform: "translate(-50%, -50%)",
            width: "650px",
            maxWidth: "100%",
            borderRadius: '10px',
            borderWidth: '0px',
        }
    };

    useEffect(() => {
    }, [])

    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Change Modal"

        >
            <React.Fragment>
                <button onClick={props.closePopup} className="custom-popup-close">X</button>
                <div className="login-main-wrapper login-form-1">
                    <div className="login-from-wrapper">

                        <h4 className="text-left main-header-title">
                        Waiting list
                                </h4>
                                <p>If this trip become available for this any date we will keep you updated.</p>
                        <form onSubmit={handleSubmit} id="trip_form">
                            <div className="form-group">
                                <label htmlFor="exampleInputEmail1">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    className="form-control"
                                    placeholder="First Name"
                           

                                    required
                                />
                            </div>
                            <div className="form-group first-example">
                                <label htmlFor="exampleInputEmail1">Last Name</label>
                                <input
                                    type="text"
                                    name="last_name"
                                    className="form-control"
                                    placeholder="Last Name"
                                 
                                    required
                                />
                            </div>
                            <div className="form-group first-example">
                                <label htmlFor="exampleInputEmail1">Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    className="form-control"
                                    placeholder="Email"
                                    required
                                />
                                
                            </div>
                            <div className="form-group first-example">
                                <label htmlFor="exampleInputEmail1">Phone Number</label>
                                {/* <input
                                    type="number"
                                    name="phone_number"
                                    className="form-control"
                                    placeholder="Phone Number"
                                    required
                                /> */}
                                 <PhoneInput
                                    country={'sg'}
                                    value={inputs.phone_number}
                                    onChange={phone => setInputs({ ...inputs, phone_number: phone })}
                                    className="form-control"
                                />
                                
                            </div>
                            {apis.error_msg ? <span style={{ color: "red" }}>{apis.error_msg}</span> : ''}
                            <button type="submit" className="btn btn-primary btnSubmit" disabled={isSubmitted} style={{display: "block"}}>
                                {isSubmitted && (<span className="spinner-border spinner-border-sm"></span>)} Save
                                </button>
                            
                        </form>
                    </div>

                </div>

            </React.Fragment>
        </ReactModal>
    );
};

export default WaitListModal;
