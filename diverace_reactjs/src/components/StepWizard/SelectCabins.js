import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import ReactModal from 'react-modal';
import BackNextButton from "./BackNextButton";
import Summary from "../Summary/index";
import PopupCabinType from "./PopupCabinType";
import PopupCabin from "./PopupCabin";
import { updateForm, updateSummary, fetchCabins, updateAmount } from "../../store/actions/StepWizardAction";

const SelectCabins = () => {

  const redux_state = useSelector((state) => state.StepWizardReducer);

  const vessel_id = useSelector((state) => state.StepWizardReducer.vessel_type);
  const pax_state = useSelector((state) => state.StepWizardReducer.pax);
  const cabins_list = useSelector((state) => state.StepWizardReducer.cabins_list);
  const cabin_id = useSelector((state) => state.StepWizardReducer.cabin_type);
  const selected_cabins_state = useSelector((state) => state.StepWizardReducer.cabin_types);

  const dispatch = useDispatch();

  const [values, setValues] = useState({
    pax: pax_state
  });

  const [valuescabins, setvaluescabins] = useState({
    selected_cabins: selected_cabins_state
  });

  const [showPopup, setShowPopup] = useState(false);
  const [number_selected_cabins, setNumber_selected_cabins] = useState(0);
  const [passPropToPaxModel, setPassPropToPaxModel] = useState({ 'id': 0, "type": '' });

  const [type, setType] = useState('');
  const [options, setOptions] = useState([]);
  const [isDisabled, setIsDisabled] = useState(false);

  //Show-PopUp
  const togglePopup = () => {
    setShowPopup(!showPopup)
  }
  //Cabin-validation-popup
  const [validateCabin, setValidateCabin] = useState(false)
  //popUp Style
  const customStyles = {

    content: {
      top: '50%',
      left: '50%',
      right: 'auto',
      bottom: 'auto',
      transform: 'translate(-50%, -50%)',
      width: "auto",
      maxWidth: '30vw',
      maxHeight: "90vh",
      overflow: 'auto',
      padding: 0,
    }

  };
  //add-cabins
  const changeCabinField = (e) => {

    e.preventDefault();

    let cabin_type = e.currentTarget.getAttribute("data-cabintype");

    /* Person options */
    const paxOptions = pax_state.map((p, i) => ({ value: `Person${i + 1}`, label: `Person${i + 1}`, type: p.type, is_used: p.is_used }));

    setOptions([...paxOptions]);

    let cabinType = e.currentTarget.getAttribute("data-cabin");
    let cabinTitle = e.currentTarget.getAttribute("data-cabintitle");
    let cabinStateType = e.currentTarget.getAttribute("data-statetype");
    let cabinPrice = e.currentTarget.getAttribute("data-cabinprice");

    let seat_type = e.currentTarget.getAttribute("data-seattype");

    var vals_cabins = [...valuescabins.selected_cabins];
    let cabinIndex = vals_cabins.findIndex(x => x.id === Number(cabinType));


    setType(cabin_type);

    if (cabin_type == '2pax_spot') {
      /* If selected cabin then remove  */
      if (cabinIndex !== -1) {

        /* Find selected persons Start */
        var cabinIdArray = vals_cabins.find(a => a.id === Number(cabinType)).person;
        let vals_pax = [...values.pax];
        vals_pax.forEach((element, index) => {
          let findPerson = `Person${index + 1}`;
          if (cabinIdArray.includes(findPerson)) {
            vals_pax[index]['is_used'] = false;
          }
        });

        setValues({ pax: vals_pax });
        /* Find selected persons End */

        vals_cabins.splice(cabinIndex, 1);
        setvaluescabins({ selected_cabins: vals_cabins });
        dispatch(updateForm(cabinStateType, vals_cabins));
        return;
      }


      /* Validate Cabin */
      let checkType = cabin_type == "solo_spot" ? "solo" : "2pax";
      var filterOptions = paxOptions.filter((obj) => {
        return obj.type === checkType && obj.is_used === false;
      });


      if (filterOptions.length === 0) {
        setValidateCabin(true);
        return;
      }
      /* Validate Cabin */


      /* if not selected then open cabin modal */
      setPassPropToPaxModel({ "id": Number(cabinType), "title": cabinTitle, "seat_type": seat_type, "price": cabinPrice, "person": [] });
      togglePopup();
      return;

    } else {

      /* if not selected then open cabin modal */
      if (cabinIndex === -1) {


        /* Validate Cabin */
        let checkType = cabin_type == "solo_spot" ? "solo" : "2pax";
        var filterOptions = paxOptions.filter((obj) => {
          return obj.type === checkType && obj.is_used === false;
        });


        if (filterOptions.length === 0) {
          setValidateCabin(true);
          return;
        }
        /* Validate Cabin */

        setPassPropToPaxModel({ "id": Number(cabinType), "title": cabinTitle, "seat_type": seat_type, "price": cabinPrice, "person": [] });
        togglePopup();

      } else {
        /* then remove */

        /* Find selected persons  */
        var cabinIdArray = vals_cabins.find(a => a.id === Number(cabinType)).person;
        let vals_pax = [...values.pax];
        vals_pax.forEach((element, index) => {
          let findPerson = `Person${index + 1}`;
          if (cabinIdArray.includes(findPerson)) {
            vals_pax[index]['is_used'] = false;
          }
        });
        setValues({ pax: vals_pax });
        /* Find selected persons End */

        vals_cabins.splice(cabinIndex, 1);
        setvaluescabins({ selected_cabins: vals_cabins });
        dispatch(updateForm(cabinStateType, vals_cabins));
      }

    }

  };

  useEffect(() => {

    const stateType = "pax";
    const PERSON_STATE = "passenger";
    dispatch(updateForm(stateType, values.pax));
    dispatch(updateSummary(stateType, values.pax));

    let passenger = values.pax.length;
    dispatch(updateForm(PERSON_STATE, Number(passenger)));

    let PAX_ARRAY = values.pax;
    
    for (let i = 0; i < PAX_ARRAY.length; i++) {
    
      if (!PAX_ARRAY[i]['is_used'] ) {
        setIsDisabled(true)
          break;
      } else {
        setIsDisabled(false)
      }
  }


  }, [values, showPopup]);

  useEffect(() => {

    window.scrollTo(0, 0);
    // if (cabins_list.length === 0) {
    dispatch(fetchCabins(vessel_id, Number(redux_state.tripDate_type)));
    // }
  }, [vessel_id]);

  useEffect(() => {

    setvaluescabins({ selected_cabins: selected_cabins_state });

    let no_of_selected_cabins = 0;

    if (selected_cabins_state.length > 0) {
      for (let i = 0; i < selected_cabins_state.length; i++) {
        no_of_selected_cabins = selected_cabins_state[i].type == 'solo' ? no_of_selected_cabins + 1 : (selected_cabins_state[i].seat === 'both' ? no_of_selected_cabins + 2 : no_of_selected_cabins + 1);
      }
    }

    setNumber_selected_cabins(Number(no_of_selected_cabins));
  }, [selected_cabins_state]);


  //-----------Add Pax
  const handleAddPaxFields = (e) => {
    e.preventDefault();


    setValues({ pax: [...values.pax, { solo: true, two: false, type: "solo", gender: ['male'], is_used: false, name: '', email: '',phone_number:'', age: '', emailValid:false, phoneValid:false, ageValid:false }] })

    //dispatch(updateForm('cabin_types', []));
    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateAmount(0));
  };

  //-----------Remove Pax
  const handleRemovePaxFields = (e) => {
    e.preventDefault();
    let arrIndex = e.currentTarget.getAttribute("data-mainindex");

    /* Find selected persons in cabin  */

    var vals_cabins = [...valuescabins.selected_cabins];
    let findPerson = `Person${Number(arrIndex) + 1}`;


    vals_cabins.forEach((c_obj, i, object) => {

      const filteredPersonArray = c_obj.person.filter(p => p !== findPerson);


      vals_cabins[i]['person'] = [...filteredPersonArray];
      if (filteredPersonArray.length == 0) {
        object.splice(i, 1);
      }

    });

    setvaluescabins({ selected_cabins: vals_cabins });
    dispatch(updateForm('cabin_types', vals_cabins));

    /* Find selected persons End */


    let vals_pax = [...values.pax];
    vals_pax.splice(arrIndex, 1);

    setValues({ pax: vals_pax });
    //dispatch(updateForm('cabin_types', []));
    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateAmount(0));
  }

  //------- Change Pax Type
  const changePaxType = (val, e) => {
    e.preventDefault();
    let arrIndex = e.currentTarget.getAttribute("data-index");

    /* Find selected persons in cabin  */

    var vals_cabins = [...valuescabins.selected_cabins];
    let findPerson = `Person${Number(arrIndex) + 1}`;


    vals_cabins.forEach((c_obj, i, object) => {

      const filteredPersonArray = c_obj.person.filter(p => p !== findPerson);


      vals_cabins[i]['person'] = [...filteredPersonArray];
      if (filteredPersonArray.length == 0) {
        object.splice(i, 1);
      }

    });

    setvaluescabins({ selected_cabins: vals_cabins });
    dispatch(updateForm('cabin_types', vals_cabins));

    /* Find selected persons End */

    let vals_pax = [...values.pax];

    if (val == 2) {
      // const { type , solo, two } = vals_pax[arrIndex];

      vals_pax[arrIndex].type = '2pax';
      vals_pax[arrIndex].solo = false;
      vals_pax[arrIndex].two = true;
      vals_pax[arrIndex].is_used = false;
      //vals_pax[arrIndex].gender.push('male');

    } else {
      vals_pax[arrIndex].type = 'solo';
      vals_pax[arrIndex].solo = true;
      vals_pax[arrIndex].two = false;
      vals_pax[arrIndex].two = false;
      vals_pax[arrIndex].is_used = false;
      vals_pax[arrIndex].gender.splice(1, 1);
    }
    setValues({ pax: vals_pax });
    //dispatch(updateForm('cabin_types', []));
    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateAmount(0));

  }

  //-------- Change Gender type
  const changeGenderType = (gender, e) => {
    e.preventDefault();
    let genderindex = e.currentTarget.getAttribute("data-genderindex");
    let arrkey = e.currentTarget.getAttribute("data-genderkey");

    let vals_pax_gender = [...values.pax];

    let genderArr = vals_pax_gender[genderindex].gender;

    genderArr.splice(arrkey, 1, gender);
    vals_pax_gender[genderindex].gender = genderArr;

    setValues({ pax: vals_pax_gender });

  }

  //---------- Pax Component
  function createPaxUI() {

    return values.pax.map((pax_obj, i) =>

      <div className="p-2 row rm-padding-mobile" key={i}>
        <a className="cust-item-center remove-text-decoration remove-icon-wrapper" href="#" onClick={handleRemovePaxFields} data-mainindex={i} style={{ visibility: i > 0 && values.pax.length - 1 == i ? '' : 'hidden' }}>
          <img
            src={process.env.PUBLIC_URL + "/assets/crose-icon.svg"}
            alt="Remove Icon"
            className="remove-icon pr-10"
          />
        </a>

        <div className="col-md-5 itinerary-wrapper single-pax">
          <div className={`single-itinerary cls ${pax_obj.solo ? "cls-border avoid-clicks" : "border-rd"} col-md-12`} data-index={i} onClick={(e) => changePaxType(1, e)}>
            <img
              src={process.env.PUBLIC_URL + "/assets/ic_user_single.png"}
              alt="Itinerary Icon"
              width={30}
              className="itinerary-icon"
            />
            <span className="itinerary-title ellipsis-title">Solo spot{" "}</span>
          </div>
        </div>
        <div className="col-md-5 itinerary-wrapper double-pax">
          <div className={`single-itinerary cls ${pax_obj.two ? "cls-border avoid-clicks" : "border-rd"} col-md-12`} data-index={i} onClick={(e) => changePaxType(2, e)}>
            <img
              src={process.env.PUBLIC_URL + "/assets/ic_user_pax.png"}
              alt="Itinerary Icon"
              width={30}
              className="itinerary-icon"
            />
            <span className="itinerary-title ellipsis-title">2 pax cabin{" "}</span>
          </div>
        </div>
        <div className="col-md-12 itinerary-wrapper double-pax">
          <GenderSection index={i} key={i} genderKey={0} genderObj={pax_obj.gender[0]} />
        </div>
      </div>

    )
  }

  const GenderSection = (props) => {

    return (
      <div className="p-2 row rm-padding-mobile" data-genderindex={props.index}>
        <i
          className="fa fa-times padd-in-mobile"
          aria-hidden="true"
          style={{ color: "#3396d8", visibility: "hidden" }}
        ></i>
        <div className="cls bg-none d-flex-ceter people-count">
          <p className="num ">Person{props.index + 1}</p>
        </div>
        <div className="col-md-4 itinerary-wrapper male-gender">
          <div className={`single-itinerary cls ${props.genderObj === 'male' ? "cls-border avoid-clicks" : "border-rd"} col-md-12`} data-genderindex={props.index} data-genderkey={props.genderKey} onClick={(e) => changeGenderType('male', e)}>

            <img
              src={process.env.PUBLIC_URL + "/assets/ic_male.png"}
              alt="Itinerary Icon"
              width={30}
              className="itinerary-icon"
            />
            <span className="itinerary-title ellipsis-title">Male{" "}</span>
          </div>
        </div>
        <div className="col-md-4 itinerary-wrapper female-gender">
          <div className={`single-itinerary cls ${props.genderObj === 'female' ? "cls-border avoid-clicks" : "border-rd"} col-md-12`} data-genderindex={props.index} data-genderkey={props.genderKey} onClick={(e) => changeGenderType('female', e)}>

            <img
              src={process.env.PUBLIC_URL + "/assets/ic_female.png"}
              alt="Itinerary Icon"
              width={30}
              className="itinerary-icon"
            />
            <span className="itinerary-title ellipsis-title">Female{" "}</span>
          </div>
        </div>
      </div>
      // </div> 
    )
  }

  return (
    <React.Fragment>
      <div id="step-3" className="container content-center step-main-wrapper" style={{ display: 'block' }}>
        {/* Cabin validation */}
        {validateCabin && <ReactModal
          isOpen={validateCabin}
          style={customStyles}
          contentLabel="Validation Modal"
          ariaHideApp={false}
        >

          <div className="mainCabinPopup">
            <h4 className="text-center heading pt-10 pb-10">
              <i className="fa fa-exclamation-triangle text-error" aria-hidden="true"></i>
            </h4>
            <h4 className="heading text-center text-error">Oops sorry!</h4>
            <p className=" text-center pt-20 pb-10">Please choose related Pax type or Maximum Cabin Selected As Per Pax!</p>
          </div>
          <div className="bottom-btn-section" onClick={(e) => { setValidateCabin(false) }}>
            <span>Dismiss</span>
          </div>
        </ReactModal>
        }
        {/* Cabin validation */}
        <div className="row">
          <div className="body-left col-md-7">
            <div className="select-area pax-wrapper">
              <h4 className="heading select-number-of-pax-title">Number of Pax</h4>
              <a href="#" className="add remove-text-decoration" onClick={handleAddPaxFields}>
                <img
                  src={process.env.PUBLIC_URL + "/assets/add-icon.svg"}
                  alt="Add Icon"
                  className="add-icon pr-10"
                /> <span className="add-more-title">Add more</span>
              </a>
              {/* pax */}
              {createPaxUI()}
              {/* pax */}

            </div>


            <div className="img-box row select-cabin-row">
              <div className="col-md-11">
                <div className="row">
                  <div className="col-sm-12 col-md-12">
                    <h4 className="heading text-left">Select your Cabin</h4>
                    {/* <h5 className="text-danger">You can purchase Course or Rental-equipment maximum {redux_state.passenger} per pax</h5> */}
                  </div>
                </div>

                <div className="cabin-main-wrapper">
                  <div className="row no-gutters row-cabindetails-wrapper">
                    <div className="col-md-12">
                      <div className="row">
                        <div className="card-deck">

                          {showPopup && <PopupCabin showPopupState={showPopup} closePopup={togglePopup} validatePopUp={setValidateCabin} paxState={passPropToPaxModel} options={options} type={type} />}

                          {!cabins_list || cabins_list == undefined || cabins_list.length === 0 ? (<div className="text-center w-100"> NO RECORD FOUND </div>) : cabins_list.map((cabin_obj, z) => {
                            return (
                              <div className="col-12 col-sm-6 col-md-6 single-cabin-wrapper custom-d-flex" key={z}>
                                <div className={`card ${selected_cabins_state.find(item => item.id === cabin_obj.id) === undefined ? "" : "cls-border"}`}>
                                  <div className="pdf-thumb-box"
                                    style={{
                                      display: "block",
                                      margin: "inherit",
                                      textAlign: "center",
                                      backgroundColor: "#f0f0f0",
                                      backgroundImage: `url(${cabin_obj.gallery_image[0]['url']})`

                                    }}
                                  >
                                    <a href="#"
                                      data-cabin={cabin_obj.id}
                                      data-cabintitle={cabin_obj.title}
                                      data-statetype={`cabin_types`}
                                      data-cabinprice={cabin_obj.cabin_price}
                                      data-cabintype={cabin_obj.pax_for_persons}
                                      data-seattype={cabin_obj.seat}
                                      onClick={changeCabinField} >

                                      <div className="pdf-thumb-box-overlay"></div>
                                    </a>
                                  </div>
                                  <div className="main-card-body" >
                                    <div className="card-body" >
                                      <h5 className="card-title">{cabin_obj.title}</h5>
                                      <span className="summary-text-info common-price-text">S$ {String(cabin_obj.cabin_price).replace(/(.)(?=(\d{3})+$)/g, '$1,')} </span>
                                      <span className="card-title type-cabin-title">{cabin_obj.pax_for_persons == "solo_spot" ? "SOLO" : "DOUBLE"} </span>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            )
                          })}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

          {/* Summary */}
          <Summary step={3}></Summary>
          {/* Summary */}

        </div>
      </div>
      <div
        className="bottom-text pt-4"
        style={{
          display: "block",
          margin: "inherit",
          textAlign: "center",
        }}
      ></div>
      <BackNextButton
        back={2}
        next={4}
        label={`Next`}
        disabled={isDisabled}
      ></BackNextButton>
    </React.Fragment>
  );
};

export default SelectCabins;
