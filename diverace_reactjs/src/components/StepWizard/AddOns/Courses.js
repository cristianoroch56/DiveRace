import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import { updateForm } from "../../../store/actions/StepWizardAction";
const stateType = "pax";


const paxParams = {
    person_id: undefined, is_used: false, name: '', email: '', phone_number: '', age: '', emailValid: false, phoneValid: false, ageValid: false,
    cabin_type: '', course_id: 0, course_title: '', course_price: 0, course_used: true,
    equipments: [], equipment_used: true,
}

const Courses = () => {


    const dispatch = useDispatch();
    const pax_state = useSelector((state) => state.StepWizardReducer.pax);
    const pax_cabin = useSelector((state) => state.StepWizardReducer.pax_cabin);
    const courses_list = useSelector((state) => state.StepWizardReducer.courses_list);
    const defaultCurrencyCode = useSelector((state) => state.StepWizardReducer.default_currency_code);
    const default_currency = useSelector((state) => state.StepWizardReducer.default_currency);

    const [isDisabled, setIsDisabled] = useState(false);

    const checkIsDisabled = () => {
        const local_pax = [...pax_state];
        setIsDisabled(false);
        const findUsedCourseLength = local_pax.filter(p => p.course_used == true).length;
        if (findUsedCourseLength === local_pax.length) {
            setIsDisabled(true);
        }
    }

    const setPaxFormat = () => {
        const paxCabinArray = [...pax_cabin];
        const newPaxArray = [];
        const updated_pax_array = paxCabinArray.map((paxObj, i) => {
          if(paxObj.type === '2pax') {
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
        //const newPaxArray =  setPaxFormat();
        //dispatch(updateForm('pax', [...newPaxArray]));
    },[])


    useEffect(() => {

    
        checkIsDisabled()
        dispatch(updateForm('new_courses_price',0));
        const TOTAL_COURSE_PRICE = pax_state.reduce((prev,next) => prev + Number(next.course_price),0);
        dispatch(updateForm('new_courses_price',Number(TOTAL_COURSE_PRICE)));
        
    }, [pax_state])


    const addPax = () => {
        const local_pax = [...pax_state];
        const findUsedCourseLength = local_pax.filter(p => p.course_used == true).length;
        local_pax[findUsedCourseLength].course_used = true;
        dispatch(updateForm(stateType, local_pax));
    }

    const removePax = (e, index) => {
        const local_pax = [...pax_state];
        local_pax[index].course_used = false;
        local_pax[index].course_id = 0;
        local_pax[index].course_title = '';
        local_pax[index].course_price = 0;
        dispatch(updateForm(stateType, local_pax));
    }

    const selectCourse = (e, index) => {

        let courseId = e.currentTarget.getAttribute("data-course");
        let courseTitle = e.currentTarget.getAttribute("data-coursetitle");
        let coursePrice = e.currentTarget.getAttribute("data-courseprice");
        const local_pax = [...pax_state];

        if (local_pax[index].course_id == courseId) {
            local_pax[index].course_id = 0;
            local_pax[index].course_title = '';
            local_pax[index].course_price = 0;
        } else {
            local_pax[index].course_id = courseId;
            local_pax[index].course_title = courseTitle;
            local_pax[index].course_price = coursePrice;
        }

        dispatch(updateForm(stateType, [...local_pax]));

    }

    const CoursesUI = ({ courseId, paxIndex }) => {

        return courses_list.map((course_obj, i) =>
            <React.Fragment key={i}>
                <div className="col-12 col-sm-6 col-md-6  custom-single_cabin-wrapper single-cabin-wrapper custom-d-flex"
                    data-course={course_obj.id}
                    data-coursetitle={course_obj.title}
                    data-courseprice={course_obj.course_price}
                    onClick={(e) => selectCourse(e, paxIndex)}
                >
                    <div className={`card ${courseId == course_obj.id ? "cls-border" : ""}`}>
                        <div className="main-card-body">
                            <div className="card-body">
                                <h5 className="card-title">{course_obj.title}</h5>
                                <span className="summary-text-info common-price-text"> <PriceSpan price={course_obj.course_price}/> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </React.Fragment>
        )
    }

    const PriceSpan =  ({price}) => {
        let changedPrice;
        if(default_currency === 'THB') {
            changedPrice = price*23.46;
        } else if(default_currency === 'USD') {
            changedPrice = price*0.75;
        } else {
            changedPrice = price;
        }
        return (
            <span>{defaultCurrencyCode} {Math.round(changedPrice)}</span>
        )
    }
    
    return (
        <>

            {pax_state.length > 0 ? (
                pax_state.map((pax, index) =>
                    <div className="p-2 row rm-padding-mobile" key={index} id={`pax_${index}`}>

                        <a className="cust-item-center remove-text-decoration remove-icon-wrapper" href="#" onClick={(e) => removePax(e, index)} style={{ visibility: index > 0  ? '' : 'hidden' }} >
                            <img
                                src={process.env.PUBLIC_URL + "/assets/crose-icon.svg"}
                                alt="Remove Icon"
                                className="remove-icon pr-10"
                            />
                        </a>

                        <div className="col-md-5 itinerary-wrapper single-pax">
                            <div className="cls bg-none people-count"><p className="num ">Person{index + 1}</p></div>
                        </div>


                        <div className="cabin-main-wrapper custom-cabin-wrapper">
                            <div className="row no-gutters row-cabindetails-wrapper">
                                <div className="col-md-12">
                                    <div className="row">
                                        <div className="card-deck">
                                            <CoursesUI courseId={pax.course_id} paxIndex={index}></CoursesUI>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                )
            ) : 'Loading...'}


           {/*  <a href="#/" className={`add remove-text-decoration ${isDisabled ? 'add_more_btn_disabled' : ''}`} onClick={!isDisabled ? addPax : undefined}>
                <img
                    src={process.env.PUBLIC_URL + "/assets/add-icon.svg"}
                    alt="Add Icon"
                    className="add-icon pr-10"
                /> <span className="add-more-title">Add more</span>
            </a> */}
        </>
    )

}

export default Courses;