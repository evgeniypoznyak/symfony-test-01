<?php
/**
 * Created by IntelliJ IDEA.
 * @author Evgeniy
 * Date: 2019-01-14
 */

namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{
    public const EDIT = 'edit';
    public const DELETE = 'delete';
    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject): bool
    {
        // Always calls first!
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        // only vote on MicroPost objects inside this voter
        if (!$subject instanceof MicroPost) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        if ($this->decisionManager->decide($token, [User::ROLE_ADMIN])) {
            return true;
        }

        $authenticatedUser = $token->getUser();

        if (!$authenticatedUser instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var MicroPost $microPost */
        $microPost = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($microPost, $authenticatedUser);
            case self::DELETE:
                return $this->canDelete($microPost, $authenticatedUser);
        }

        throw new \LogicException('This code should not be reached!');

    }


    /**
     * @param MicroPost $microPost
     * @param User $user
     * @return bool
     */
    public function canEdit(MicroPost $microPost, User $user): bool
    {

        // this assumes that the data object has a getUser() method
        // to get the entity of the user who owns this data object
        return $user->getId() === $microPost->getUser()->getId();

    }

    /**
     * @param MicroPost $microPost
     * @param User $user
     * @return bool
     */
    public function canDelete(MicroPost $microPost, User $user): bool
    {
        return $this->canEdit($microPost, $user);
    }

}
