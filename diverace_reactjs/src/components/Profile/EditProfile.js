import React, { useEffect, useState } from "react";
import '../Login/index.css';
import ReactModal from 'react-modal';
import { updateUser } from "../../store/actions/AuthAction"
import { useDispatch, useSelector } from "react-redux";
import ApiServices from "../../services/index"
import moment from "moment"
import PhoneInput from 'react-phone-input-2'
import 'react-phone-input-2/lib/style.css'

const EditProfile = (props) => {

    const userLoginData = useSelector((state) => state.user.profile);
    const [userinfo, setUserinfo] = useState('');
    const [isSubmitted, setIsSubmitted] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const dispatch = useDispatch();

    const [inputs, setInputs] = useState({
        first_name: "",
        last_name: "",
        user_phone_number: "",
        user_age: "",
        user_gender: ""
    });

    const [apis, setApis] = useState({
        error_msg: ''
    })

    const updateProfileDetails = () => {
        let display_name = `${inputs.first_name}${inputs.last_name}`;
        let user_age = inputs.user_age;
        let user_phone_number = inputs.user_phone_number;
        let user_gender = inputs.user_gender;
        const user_profile = { ...userLoginData, display_name: display_name, user_age: user_age, user_phone_number: user_phone_number, user_gender: user_gender }
        dispatch(updateUser(user_profile))
    }

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitted(true);

        var formData = new FormData(document.getElementById("user_update_form"));
        const USER_ID = userLoginData.ID;
        formData.append(
            "user_id", USER_ID
        );

        formData.append(
            "user_phone_number", inputs.user_phone_number
        );

        let resp = await ApiServices.profileUpdate(formData);
        setIsSubmitted(false);
        if (resp.data.status) {
            updateProfileDetails();
            window.$.toast({
                heading: "Profile Details",
                text: "Profile Details Successfully Updated",
                position: 'bottom-right',
                icon: 'info',
                hideAfter: 2000,
                loaderBg: '#ffffff',
            });
            props.closeModal();
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


    const getUserDetails = async () => {
        setIsLoading(true)
        setUserinfo('');
        const USER_ID = userLoginData.ID;
        let resp = await ApiServices.getProfileDetails(USER_ID);
        setIsLoading(false)
        if (resp.data.status) {

            setInputs({ ...resp.data.data })
            setUserinfo({ ...resp.data.data });
        }
    }

    const handleChange = (e) => {
        setInputs({ ...inputs, [e.target.name]: e.target.value });
    };

    useEffect(() => {
        getUserDetails();
    }, [])

    return (
        <ReactModal
            isOpen={props.isShow}
            style={customStyles}
            ariaHideApp={false}
            contentLabel="Edit Profile Modal"

        >
            <React.Fragment>
                <button onClick={props.closeModal} className="custom-popup-close">X</button>
                <div className="login-main-wrapper login-form-1">
                    <div className="login-from-wrapper">


                        <h4 className="text-left main-header-title">
                            Edit Profile {isLoading && (<div><div className="spinner-border text-primary text-center" role="status"></div><small>Fetching Details</small></div>)}
                        </h4>
                        <form onSubmit={handleSubmit} id="user_update_form">
                            <div className="form-group">
                                <label htmlFor="exampleInputEmail1">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    className="form-control"
                                    placeholder="First Name"
                                    defaultValue={inputs.first_name}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                            <div className="form-group">
                                <label htmlFor="exampleInputEmail1">Last Name</label>
                                <input
                                    type="text"
                                    name="last_name"
                                    className="form-control"
                                    placeholder="Last Name"
                                    defaultValue={inputs.last_name}
                                    onChange={handleChange}
                                    required
                                />
                            </div>
                            <div className="form-group">
                                <label htmlFor="exampleInputEmail1">Phone Number</label>
                                <PhoneInput
                                    country={'sg'}
                                    value={inputs.user_phone_number}
                                    onChange={phone => setInputs({ ...inputs, user_phone_number: phone })}
                                    className="form-control"
                                />
                            </div>
                            <div className="form-group">

                                <div className="row">
                                    <div className="col-md-6">
                                        <label htmlFor="exampleInputEmail1">Birthdate</label>
                                        <input type="date" defaultValue={inputs.user_age} placeholder="Birthdate" max={moment(new Date()).subtract(1, 'day').format("YYYY-MM-DD")} className="form-control" name="user_age" required pattern="\d{4}-\d{2}-\d{2}"></input>
                                    </div>
                                    <div className="col-md-6">
                                        <label htmlFor="exampleInputEmail1">Gender</label>
                                        <select name="user_gender" onChange={(e) => setInputs({ ...inputs, user_gender: e.target.value })} value={inputs.user_gender} className="form-control" style={{ padding: '0 0.75rem', height: 46 }}>
                                            <option value="male" >Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div className="form-group">
                                {apis.error_msg ? <span style={{ color: "red" }}>{apis.error_msg}</span> : ''}
                                <button type="submit" className="btn btn-primary btnSubmit" disabled={isSubmitted} style={{ display: "block" }}>
                                    {isSubmitted && (<span className="spinner-border spinner-border-sm"></span>)} Save
                                </button>
                            </div> <br />


                        </form>
                    </div>

                </div>

            </React.Fragment>
        </ReactModal>
    );
};

export default EditProfile;
