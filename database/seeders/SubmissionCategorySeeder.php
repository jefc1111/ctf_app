<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubmissionCategory;

class SubmissionCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Friends',
                'points' => '10',
                'subtitle' => 'Relevant information involving friends of the MP',
                'description' => <<<XML
<ul>
    <li>Social Media profiles of friends shown to interact with MP</li>
    <li>Information involving friends of the MP relevant to the investigation.
    This may include meaningful interactions from Friends with the MP,
    meaningful photographs on Friend’s social media accounts, insightful comments from Friend’s concerning the
    disappearance of MP and so on….
    </li>
</ul>                
XML,
            ],
            [
                'name' => 'Employment',
                'points' => '15',
                'subtitle' => 'Relevant information involving employment of the MP',
                'description' => <<<XML
<ul>
    <li>Name of current or previous employer(s)</li>
    <li>Address of current or former employer(s)</li>
    <li>Information involving MP’s employment relevant to the investigation.
    This may include information about MP’s behavior at work, MP’s feelings toward employer and so on…
    </li>
</ul>            
XML,
            ],
            [
                'name' => 'Family',
                'points' => '20',
                'subtitle' => 'Relevant information involving Family of the MP',
                'description' => <<<XML
<ul>
    <li>Social Media profiles of family members relevant to the investigation</li>
    <li>Social Media comments from family relevant to the investigation</li>
    <li>Any other information about MP's family members relevant to the investigation</li>
</ul>              
XML,
            ],
            [
                'name' => 'Basic Subject Info',
                'points' => '50',
                'subtitle' => 'Relevant information involving Basic Info of the MP',
                'description' => <<<XML
<ul>
    <li>Aliases/Handles</li>
    <li>Relevant photos that would contribute to the investigation.
    Examples of this may include photos showcasing different hairstyles,
    manners of dress or other physical characteristics not mentioned in the MP report for example
    </li>
    <li>Forum profile(s) and/or relevant posts</li>
    <li>Dating site profile(s)/posts</li>
    <li>Social Media profile(s).
    Examples of this may include Facebook, Twitter, TikTok, Reddit, Instagram, LinkedIn and so on…
    </li>
    <li>Any other online persona or profile.
    Examples of this may include Github, Adult Entertainment websites (either customer or content creator),
    Gaming profiles, Etsy, Pinterest and so on….
    </li>
    <li>Personal websites</li>
    <li>Email address(es)</li>
    <li>Any other basic information about the MP relevant to the investigation as explained in your submission
    </li>
</ul>            
XML,
            ],
            [
                'name' => 'Advanced Subject Info',
                'points' => '100',
                'subtitle' => 'Relevant information involving Advanced Info about the MP',
                'description' => <<<XML
<ul>
    <li>Unique physical identifiers (e.g. tattoos, scars, piercings)</li>
    <li>Major medical issues/conditions. Can be physical or psychological</li>
    <li>Any information about where the MP might have gone.
    May include social media posts, social media interactions or recollections from friends/family for example
    </li>
    <li>license plate of vehicle(s)</li>
    <li>make and model of vehicle MP may be traveling in</li>
    <li>Previous missing persons history - news reports about the previous disappearance and return are
    acceptable
    </li>
    <li>Evidence of MP being deceased</li>
    <li>Evidence of MP being no longer missing</li>
    <li>Any other information about the MP that transcends Basic Subject Info relevant to the investigation</li>
</ul>           
XML,
            ],
            [
                'name' => 'Day Last Seen',
                'points' => '300',
                'subtitle' => 'Relevant information regarding the subject\'s last day seen. This can include but not limited to:',
                'description' => <<<XML
<ul>
    <li>Details about MP's physical appearance on day last seen (clothing, hair, etc) not stated in MP report</li>
    <li>Details of MP's state of mind on day last seen (mood, altercations, conversations, etc).
    This information could come from Friends/Family or from the MP themselves
    </li>
    <li>Any other new information about the MP on their last day seen relevant to the investigation</li>
</ul>             
XML,
            ],
            [
                'name' => 'Advancing the Timeline',
                'points' => '700',
                'subtitle' => 'Information showing activity from the MP after their missing date',
                'description' => <<<XML
<ul>
    <li>Activity from a social media account (including aliases) exclusively controlled by the MP after they went
    missing
    </li>
    <li>Location information since subject went missing, up to the current date.
    An example of this would be information pointing to a city they were likely living in today (while not
    narrowing down their actual physical location)
    </li>
    <li>Account creation after day last seen</li>
    <li>CCTV picture/video of MP</li>
    <li>Any other information that showcases MP’s activities after they were reported missing</li>
</ul>               
XML,
            ],
            [
                'name' => 'Location',
                'points' => '5000',
                'subtitle' => 'Relevant information pertaining to the current location of the MP',
                'description' => <<<XML
<ul>
    <li>Current location being defined as:
    Exact location/address the subject has been in past 24 hours, or will imminently be present at. Broad
    geographical descriptions will not count for this category.
    </li>
    <li>As this is the highest point flag, it will require the highest level of accuracy and thoroughness in reporting
    and context. Speculation has no place in this intel.
    </li>
    <li>This does not include a police update saying the person was found or an obituary - this will get you 150
    points and can be under the category Advanced Subject Info.
    </li>
</ul>              
XML,
            ]
        ];

        foreach ($categories as $category) {
            SubmissionCategory::create([
                'name' => $category['name'],
                'subtitle' => $category['subtitle'],
                'description' => $category['description'],
                'points' => $category['points']
            ]);
        }
    }
}
