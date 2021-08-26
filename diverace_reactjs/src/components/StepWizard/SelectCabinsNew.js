import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import BackNextButton from "./BackNextButton";
import Icon from '../../icons/index'
import Summary from "../Summary/index";
import ReactTooltip from 'react-tooltip';
import { updateForm, setPassenger } from "../../store/actions/StepWizardAction";



const paxParams = {
  person_id: undefined, is_used: false, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
  cabin_type: '', course_id: 0, course_title: '', course_price: 0, course_used: true,
  equipments: [], equipment_used: true,
}

const SelectCabinsNew = () => {

  const pax_cabin = useSelector((state) => state.StepWizardReducer.pax_cabin);
  const updated_pax_flag = useSelector((state) => state.StepWizardReducer.updated_pax_flag);
  const dispatch = useDispatch();


  const updateSeat = (type, e) => {

    let pax_index = e.currentTarget.getAttribute("data-paxindex");

    const paxCabinArray = [...pax_cabin];
    paxCabinArray[pax_index].cabin_type = type;
    dispatch(updateForm('pax_cabin', paxCabinArray));
    dispatch(updateForm('updated_pax_flag', 0));

  };


  const handleAddPaxFields = (e) => {
    e.preventDefault();

    const paxCabinArray = [...pax_cabin, { solo: true, two: false, type: "solo", gender: ['male'], cabin_type: 'shared' }];
    dispatch(updateForm('pax_cabin', paxCabinArray));

    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateForm('updated_pax_flag', 0));

  };


  const handleRemovePaxFields = (e) => {
    e.preventDefault();


    let arrIndex = e.currentTarget.getAttribute("data-mainindex");

    const paxCabinArray = [...pax_cabin];
    paxCabinArray.splice(arrIndex, 1);
    dispatch(updateForm('pax_cabin', paxCabinArray));

    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateForm('updated_pax_flag', 0));
    updateAddOns();
  }


  const changePaxType = (val, e) => {

    e.preventDefault();
    let arrIndex = e.currentTarget.getAttribute("data-index");


    const paxCabinArray = [...pax_cabin];
    if (val == 2) {

      paxCabinArray[arrIndex].type = '2pax';
      paxCabinArray[arrIndex].solo = false;
      paxCabinArray[arrIndex].two = true;
      paxCabinArray[arrIndex].gender = [paxCabinArray[arrIndex].gender[0], 'male'];
      paxCabinArray[arrIndex].cabin_type = 'single';

    } else {

      paxCabinArray[arrIndex].type = 'solo';
      paxCabinArray[arrIndex].solo = true;
      paxCabinArray[arrIndex].two = false;
      paxCabinArray[arrIndex].gender.splice(1, 1);
      paxCabinArray[arrIndex].cabin_type = 'shared';
    }


    dispatch(updateForm('pax_cabin', paxCabinArray));

    dispatch(updateForm('courses_types', []));
    dispatch(updateForm('rental_equipment_types', []));
    dispatch(updateForm('updated_pax_flag', 0));
  
  }


  const changeGenderType = (gender, e) => {
    e.preventDefault();

    let genderindex = e.currentTarget.getAttribute("data-genderindex");
    let arrkey = e.currentTarget.getAttribute("data-genderkey");

    const paxCabinArray = [...pax_cabin];
    let genderArr = paxCabinArray[genderindex].gender;

    genderArr.splice(arrkey, 1, gender);
    paxCabinArray[genderindex].gender = genderArr;

    dispatch(updateForm('pax_cabin', paxCabinArray));
    dispatch(updateForm('updated_pax_flag', 0));

  }

  //---------- ---------------------------------------Child  Component start------------------------------------//
  function createPaxUI() {

    return (
      pax_cabin.map((pax_obj, i) =>
        <React.Fragment key={i}>
          <div className="p-2 row rm-padding-mobile" id={`pax__${i}`}>
            <a className="cust-item-center remove-text-decoration remove-icon-wrapper" href="#" onClick={handleRemovePaxFields} data-mainindex={i} style={{ visibility: i > 0 ? '' : 'hidden' }}>
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
                <span className="itinerary-title ellipsis-title">Solo Guest{" "}</span>
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
              {pax_obj.two && (
                <GenderSection index={i} key={i} genderKey={1} genderObj={pax_obj.gender[1]} />
              )}
            </div>
          </div>

          {pax_obj.solo && (
            <CabinUiComponent pax_index={i} key={i} type={pax_obj.cabin_type}></CabinUiComponent>
          )}

        </React.Fragment>


      )
    )
  }


  const CabinUiComponent = (props) => {
    const pax_index = props.pax_index;
    const seat_type = props.type;

    return (
      <React.Fragment>
        <div className="img-box row select-cabin-row" >
          <div className="col-md-11">
            <div className="row">
              <div className="col-sm-12 col-md-12">

              </div>
            </div>

            <div className="cabin-main-wrapper custom-cabin-wrapper">
              <div className="row no-gutters row-cabindetails-wrapper">
                <div className="col-md-12">
                  <div className="row">
                    <div className="card-deck">




                      <div className="col-12 col-sm-12 col-md-12 custom-single_cabin-wrapper single-cabin-wrapper custom-d-flex"  >

                        <div className={`card`}>

                          <div className="main-card-body" >
                            <div className="card-body">
                              {/* <h5 className="card-title">Cabin Type</h5> */}

                              <div className="cabin-pax-data" style={{ textAlign: 'center' }}>


                                <div data-tip data-for='sharedNote' className={`form-check-inline radio-btn-fancy shared__radio`}>

                                  <input

                                    type="radio"
                                    name={`cabin_field${pax_index}`}
                                    value="shared"
                                    id={`cabinsolo${pax_index}`}
                                    checked={seat_type === 'shared'}
                                    className="form-control"
                                    data-value="shared"
                                    data-paxindex={pax_index}
                                    onClick={(e) => updateSeat('shared', e)}

                                  />
                                  <label htmlFor={`cabinsolo${pax_index}`}> Share Cabin </label>
                                  <Icon name="note" />


                                </div>


                                <ReactTooltip id='sharedNote' effect='solid'>
                                  <span>Share the cabin with another guest. <br/> We will do our best to pair you with the same gender <br/> but please note that it is not guaranteed!</span>
                                </ReactTooltip>


                                <div data-tip data-for='singleNote' className={`form-check-inline radio-btn-fancy single__radio`} >
                                  <input
                                    type="radio"

                                    name={`cabin_field${pax_index}`}
                                    value="single"
                                    id={`cabin2pax${pax_index}`}
                                    className="form-control"
                                    checked={seat_type === 'single'}
                                    data-value="single"
                                    data-paxindex={pax_index}
                                    onClick={(e) => updateSeat('single', e)}

                                  />
                                  <label htmlFor={`cabin2pax${pax_index}`}>
                                    Single Supplement
                                  </label>
                                  <Icon name="note" />
                                </div>

                                <ReactTooltip id='singleNote' effect='solid'>
                                  <span>Everyone loves privacy! <br/> have the whole cabin to yourself with the single supplement option.  <br/> Please note that there is a 80% surcharge for this option</span>
                                </ReactTooltip>

                              </div>


                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </React.Fragment>
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
          <p className="num" style={{ visibility: "hidden" }}>Person{props.index + 1}</p>
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
            <span className="itinerary-title ellipsis-title">Female</span>
          </div>
        </div>
      </div>
    )
  }

  const updateAddOns = () => {

    dispatch(updateForm('course_pax_options', []))
    dispatch(updateForm('new_courses_price', 0));
    dispatch(updateForm('rentals_pax_options', []));
    dispatch(updateForm('new_equipments_price', 0));
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
    window.scrollTo(0, 0);
  }, []);

  useEffect(() => {
    const newPaxArray =  setPaxFormat();
    dispatch(setPassenger(Number(newPaxArray.length)))
  }, [pax_cabin]);


  return (
    <React.Fragment>
      <div id="step-3" className="container content-center step-main-wrapper" style={{ display: 'block' }}>


        <div className="row">
          <div className="body-left col-md-7">
            <div className="select-area pax-wrapper">
              <h4 className="heading select-number-of-pax-title">Number of Pax</h4>


              {createPaxUI()}
              <a href="#" className="add remove-text-decoration" onClick={handleAddPaxFields}>
                <img
                  src={process.env.PUBLIC_URL + "/assets/add-icon.svg"}
                  alt="Add Icon"
                  className="add-icon pr-10"
                /> <span className="add-more-title">Add more</span>
              </a>
            </div>

          </div>

          <Summary step={3}></Summary>

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
        disabled={false}
      ></BackNextButton>
    </React.Fragment>
  );
};

export default SelectCabinsNew;
