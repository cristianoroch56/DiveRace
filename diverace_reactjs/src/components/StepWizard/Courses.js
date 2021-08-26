import React, { useEffect, useState } from "react";
import {
    updateForm,
} from "../../store/actions/StepWizardAction";
import { useDispatch, useSelector } from "react-redux";
import Select from "react-select";
import makeAnimated from "react-select/animated";

const animatedComponents = makeAnimated();


const Courses = ({pax_state}) => {


    const dispatch = useDispatch();
    const default_pax_options = useSelector((state) => state.StepWizardReducer.course_pax_options)
    //const pax_state = useSelector((state) => state.StepWizardReducer.pax);
    const courses_list = useSelector((state) => state.StepWizardReducer.courses_list);

    const [courses, setCourses] = useState([]);
    const [PaxOptions, setPaxOptions] = useState([])
    const [paxCourses, setPaxCourses] = useState([...default_pax_options])
    const [coursepersons, setCoursepersons] = useState([]);


    useEffect(() => {

        //setPaxCourses([...default_pax_options])
        /* Person options start*/
        const paxOptions = pax_state.map((p, i) => ({
            value: `Person${i + 1}`,
            label: `Person${i + 1}`,
            type: p.type,
            is_used: p.is_used,
            default:undefined,
            id: i,
        }));
        setPaxOptions([...paxOptions]);
        /* Person options end*/

        const courses_options = courses_list.map(c => ({ value: c.id, label: `S$${c.course_price} - ${c.title}`, title: c.title, price: c.course_price }));
        setCourses([...courses_options])
    }, [courses_list]);
    
    useEffect(() => {
        dispatch(updateForm('new_courses_price',0));
        const TOTAL_COURSE_PRICE = pax_state.reduce((prev,next) => prev + Number(next.course_price),0);
        dispatch(updateForm('new_courses_price',Number(TOTAL_COURSE_PRICE)));
    },[pax_state])


    const handleAddPaxCourse = (options) => {

        if (options != null) {

            setPaxCourses([...options]);
            dispatch(updateForm('course_pax_options',[...options]))


            let new_option = options[options.length - 1].label;
            setCoursepersons([...coursepersons, new_option]);

            const optionsValue = options.map(({ value }) => value)
            //check if remove 
            let difference = coursepersons.filter(x => !optionsValue.includes(x));
            if (difference.length > 0) {
                setCoursepersons([...optionsValue]);

                /* if person unselect then remove their coures */
                let person_array = [...pax_state];
                const updated_person_array = person_array.map((x, i) => (`Person${i + 1}` === difference[0] ? { ...x, course_id: 0 ,course_title: '', course_price: 0 }: x));
                /* set default option value undefiend */
                PaxOptions.find((p,k) => `Person${k + 1}` == difference[0]).default = undefined;
                setPaxOptions([...PaxOptions]);
                dispatch(updateForm('pax', updated_person_array));

            }


        } else {
    
            /* if user unselect all persons */
            let person_array = [...pax_state];
            const updated_person_array  = person_array.map((p) => ({...p, course_id : 0, course_title:'',course_price:0}));
            dispatch(updateForm('pax', updated_person_array));
            dispatch(updateForm('course_pax_options',[]))
            setPaxCourses([]);
            let updated_pax_options = PaxOptions.map((p,k) => ({...p, default: undefined}));
            setPaxOptions([...updated_pax_options])
        }
    };

    const changeCourse = (person_id, course_obj) => {
      

        paxCourses.find((p) => p.id == person_id).default = course_obj;
        setPaxCourses([...paxCourses]);
        dispatch(updateForm('course_pax_options',[...paxCourses]))

        let person_array = [...pax_state];
        person_array[person_id].course_id = course_obj.value;
        person_array[person_id].course_title = course_obj.title;
        person_array[person_id].course_price = course_obj.price;
        dispatch(updateForm('pax', person_array));
    }

    const customSelectStyles = {
        // For the select it self, not the options of the select
        control: (styles, { isDisabled }) => {
            return {
                ...styles,
                backgroundColor: isDisabled && "#white",
                borderColor: isDisabled ? "#d9d9d9" : "#d9d9d9",
            };
        },
        option: (styles, { }) => {
            return {
                ...styles,
                fontSize: 15,
                textAlign: "left",
            };
        },
        menuList: (styles, { }) => {
            return {
                ...styles,
                "::-webkit-scrollbar": { width: "2 !important" },
                scrollbarWidth: "none",
                maxHeight: 200,
                overflowY: "auto",
                overflowX: "hidden"
            };
        },
    };

    return (
        <React.Fragment>
            <section className="col-sm-12 col-md-10">
                <div className="row date-area border-rd date-wrapper-section course-wrapper">

                    <div className="cabin-pax-data custom-cabin-pax-data">
                        <span className="custom-pax-dropdown col-sm-11 col-11 pr-2">
                            <Select
                                placeholder="Choose Persons"
                                options={PaxOptions}
                                isMulti={true}
                                onChange={handleAddPaxCourse}
                                styles={customSelectStyles}
                                components={animatedComponents}
                                defaultValue={default_pax_options}
                            ></Select>
                        </span>
            
                        {paxCourses.length > 0 && paxCourses.map((p_obj, i) => {
                            return (
                                <React.Fragment key={p_obj.id}>
                                    <div className="people-count"><p className="num"><strong>{p_obj.label}</strong></p></div>
                                    <div className="row no-gutters mb-3">
                                        <span className="custom-pax-dropdown col-sm-10 col-9 pr-2">
                                            <Select
                                                placeholder="Choose Course"
                                                autoFocus={true}
                                                options={courses}
                                                isMulti={false}
                                                onChange={(e) => changeCourse(p_obj.id, e)}
                                                defaultValue={p_obj.default}
                                                styles={customSelectStyles}
                                            ></Select>
                                        </span>
                                    </div>
                                </React.Fragment>
                            )
                        })}
                     
                    </div>
                </div>

            </section>
        </React.Fragment>
    );
}

export default Courses;
