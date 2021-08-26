import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import SizeGuideModal from '../SizeGuideModal'
import SizeOptions from './SizeOptions'
import { updateForm } from "../../../store/actions/StepWizardAction";


const paxParams = {
    person_id: undefined, is_used: false, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
    cabin_type: '', course_id: 0, course_title: '', course_price: 0, course_used: true,
    equipments: [], equipment_used: true,
}

const stateType = "pax";
const Equipments = () => {

    const dispatch = useDispatch();
    const pax_state = useSelector((state) => state.StepWizardReducer.pax);
    const pax_cabin = useSelector((state) => state.StepWizardReducer.pax_cabin);
    const rental_equipment_list = useSelector((state) => state.StepWizardReducer.rental_equipment_list);
    const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);
    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);

    const [isDisabled, setIsDisabled] = useState(false);
    const [openSizeGuide, setOpenSizeGuide] = useState(false);
    const [sizeSelectModalOpen, setSizeSelectModalOpen] = useState(false);
    const [sizeOptions, setSizeOptions] = useState([]);
    const [sizeProps, setSizeProps] = useState(undefined);

    const checkIsDisabled = () => {
        const local_pax = [...pax_state];
        setIsDisabled(false);
        const findUsedCourseLength = local_pax.filter(p => p.equipment_used == true).length;
        if (findUsedCourseLength === local_pax.length) {
            setIsDisabled(true);
        }
    }

    const setPaxFormat = () => {
        const paxCabinArray = [...pax_cabin];
        const newPaxArray = [];
        const updated_pax_array = paxCabinArray.map((paxObj, i) => {
            if (paxObj.type === '2pax') {
                const firstObject = {
                    ...paxParams,
                    ...paxObj,
                    gender: [paxObj.gender[0]],

                }
                const secondObject = {
                    ...paxParams,
                    ...paxObj,
                    gender: [paxObj.gender[1]],

                }
                newPaxArray.push(firstObject, secondObject);
            } else {
                const firstObject = {
                    ...paxParams,
                    ...paxObj,
                }
                newPaxArray.push(firstObject);
            }
        });

        return newPaxArray;


    }

    useEffect(() => {
        //const newPaxArray = setPaxFormat();
        //dispatch(updateForm('pax', [...newPaxArray]));
    }, [])

    useEffect(() => {
    
        checkIsDisabled()

        dispatch(updateForm('new_equipments_price', 0));
        var rentals_price = 0;
        for (let index = 0; index < pax_state.length; index++) {
            if (pax_state[index].equipments.length > 0) {
                var equipments_array = pax_state[index].equipments;
                var total_price = equipments_array.reduce((prev, next) => prev + Number(next.price), 0);
                var rentals_price = rentals_price + total_price;
            }
        }
        dispatch(updateForm('new_equipments_price', Number(rentals_price)));

    }, [pax_state])

    const addPax = () => {
        const local_pax = [...pax_state];
        const findUsedCourseLength = local_pax.filter(p => p.equipment_used == true).length;
        local_pax[findUsedCourseLength].equipment_used = true;
        dispatch(updateForm(stateType, local_pax));
    }

    const removePax = (e, index) => {
        const local_pax = [...pax_state];
        local_pax[index].equipment_used = false;
        local_pax[index].equipments = [];
        dispatch(updateForm(stateType, local_pax));
    }

    const toggleSizeSelectModal = (sizes) => {
        setSizeOptions(sizes)
        setSizeSelectModalOpen(true);
    }

    const selectEquipmens = (e, index) => {

        const local_pax = [...pax_state];

        let rentalId = e.currentTarget.getAttribute("data-rental");
        const foundequipment = rental_equipment_list.find((eq) => {
            return eq.id == rentalId;
        });

        const selectedEquipments = local_pax[index].equipments;
        const filteredEquipments = selectedEquipments.filter(p => p.id != rentalId);
        if (selectedEquipments.find((equ) => equ.id == rentalId)) {
            local_pax[index].equipments = [...filteredEquipments];
        } else {

            if (foundequipment.rental_equipment_size != undefined && foundequipment.rental_equipment_size.length > 0) {
                setSizeProps({ pax: index, rental: rentalId })
                toggleSizeSelectModal(foundequipment.rental_equipment_size);
                return;
            }

            const newEquipment = {
                id: foundequipment.id,
                size: '',
                title: foundequipment.title,
                price: foundequipment.price,
            }
            local_pax[index].equipments = [...filteredEquipments, newEquipment];
        }
        dispatch(updateForm(stateType, local_pax));
    }

    const PriceSpan = ({ price }) => {
        let changedPrice;
        if (default_currency === 'THB') {
            changedPrice = price * 23.46;
        } else if (default_currency === 'USD') {
            changedPrice = price * 0.75;
        } else {
            changedPrice = price;
        }
        return (
            <span>{defaultCurrencyCode} {Math.round(changedPrice)}</span>
        )
    }

    const EquipmensUI = ({ paxIndex, equiment }) => {
        return rental_equipment_list.map((rental_obj, i) =>
            <React.Fragment key={i}>
                <div className="col-12 col-sm-6 col-md-6 custom-single_cabin-wrapper single-cabin-wrapper custom-d-flex"
                    data-rental={rental_obj.id}
                    onClick={(e) => selectEquipmens(e, paxIndex)}
                >
                    <div className={`card ${equiment.find((equ) => equ.id === rental_obj.id) ? "cls-border" : ""}`}>
                        <div className="main-card-body">
                            <div className="card-body">
                                <h5 className="card-title">{rental_obj.title}</h5>
                                <span className="summary-text-info common-price-text"> <PriceSpan price={rental_obj.price} /> </span>
                            </div>
                        </div>
                    </div>


                </div>
            </React.Fragment>
        )
    }

    return (
        <>
            {pax_state.length > 0 ? (
                pax_state.map((pax, index) =>
                    <div className="p-2 row rm-padding-mobile" key={index} id={`pax_${index}`}>

                        <a className="cust-item-center remove-text-decoration remove-icon-wrapper" href="#" onClick={(e) => removePax(e, index)} style={{ visibility: index > 0 ? '' : 'hidden' }} >
                            <img
                                src={process.env.PUBLIC_URL + "/assets/crose-icon.svg"}
                                alt="Remove Icon"
                                className="remove-icon pr-10"
                            />
                        </a>

                        <div className="col-md-5 itinerary-wrapper single-pax">
                            <div className="cls bg-none people-count">
                                <p className="num ">Person{index + 1} </p>
                            </div>

                        </div>
                        <span className="pull-right" onClick={() => setOpenSizeGuide(true)} style={{ marginRight: 65, color: '#3396d8', cursor: 'pointer', fontWeight: 600, position: 'absolute', right: 0 }}>Size Guide</span>

                        <div className="cabin-main-wrapper custom-cabin-wrapper">
                            <div className="row no-gutters row-cabindetails-wrapper">
                                <div className="col-md-12">
                                    <div className="row">
                                        <div className="card-deck">
                                            <EquipmensUI paxIndex={index} equiment={pax.equipments}></EquipmensUI>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        {openSizeGuide && (
                            <SizeGuideModal isOpen={openSizeGuide} closePopup={() => setOpenSizeGuide(false)}></SizeGuideModal>
                        )}
                        {
                            sizeSelectModalOpen && (<SizeOptions isOpen={sizeSelectModalOpen} propsData={sizeProps} options={sizeOptions} closePopup={() => setSizeSelectModalOpen(false)}></SizeOptions>)
                        }


                    </div>

                )
            ) : 'Loading...'}

            {/* <a href="#/" className={`add remove-text-decoration ${isDisabled ? 'add_more_btn_disabled' : ''}`} onClick={!isDisabled ? addPax : undefined}>
                <img
                    src={process.env.PUBLIC_URL + "/assets/add-icon.svg"}
                    alt="Add Icon"
                    className="add-icon pr-10"
                /> <span className="add-more-title">Add more</span>
            </a> */}
        </>

    )
}

export default Equipments;